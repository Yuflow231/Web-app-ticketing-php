<?php

class DBHandler {
    private static ?DBHandler $instance = null;
    private PDO $pdo;

    // =========================================================
    // CONFIGURATION — edit these or move to a config file
    // =========================================================
    private string $host   = 'localhost:3306';
    private string $dbname = 'web_app_ticketing';
    private string $user   = 'jetbrains';
    private string $pass   = 'jetbrains';
    private string $charset = 'utf8mb4';

    // =========================================================
    // SINGLETON
    // =========================================================
    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Never expose raw DB errors to the client
            error_log("DB Connection failed: " . $e->getMessage());
            throw new RuntimeException("Database connection failed.");
        }
    }

    public static function getInstance(): DBHandler {
        if (self::$instance === null) {
            self::$instance = new DBHandler();
        }
        return self::$instance;
    }


    // =========================================================
    // CORE QUERY HELPERS (private — used by the methods below)
    // =========================================================

    /** Run a SELECT, return all matching rows */
    private function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Run a SELECT, return only the first matching row (or null) */
    private function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    /** Run an INSERT / UPDATE / DELETE, return number of affected rows */
    private function execute(string $sql, array $params = []): int {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** Run an INSERT, return the new row's auto-increment id */
    private function insert(string $sql, array $params = []): int {
        $this->execute($sql, $params);
        return (int) $this->pdo->lastInsertId();
    }


    // =========================================================
    // USERS
    // =========================================================

    /**
     * Create a new user account.
     * Returns the new user's id.
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $plainPassword,
        string $role     = 'Guest',
        string $language = 'en'
    ): int {
        // Guard against values not in the enum
        if (!in_array($role, ['Administrator', 'Guest'])) {
            throw new InvalidArgumentException("Invalid role '{$role}'. Must be 'Administrator' or 'Guest'.");
        }
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        return $this->insert(
            "INSERT INTO Users (first_name, last_name, email, password_hashed, role, join_date, language)
             VALUES (?, ?, ?, ?, ?, NOW(), ?)",
            [$firstName, $lastName, $email, $hash, $role, $language]
        );
    }

    /**
     * Verify login credentials.
     * Returns the full user row on success, null on failure.
     */
    public function loginUser(string $email, string $plainPassword): ?array {
        $user = $this->fetchOne(
            "SELECT * FROM Users WHERE email = ? LIMIT 1",
            [$email]
        );
        if ($user && password_verify($plainPassword, $user['password_hashed'])) {
            unset($user['password_hashed']); // never keep the hash in memory longer than needed
            return $user;
        }
        return null;
    }

    /** Get a single user by id (password excluded) */
    public function getUserById(int $id): ?array {
        return $this->fetchOne(
            "SELECT id, first_name, last_name, email, role, join_date, language, pfp
             FROM Users WHERE id = ?",
            [$id]
        );
    }

    /** Check whether an email is already registered */
    public function emailExists(string $email): bool {
        $row = $this->fetchOne(
            "SELECT id FROM Users WHERE email = ? LIMIT 1",
            [$email]
        );
        return $row !== null;
    }

    /** Update a user's profile picture path */
    public function updateUserPfp(int $userId, string $pfpPath): bool {
        return $this->execute(
            "UPDATE Users SET pfp = ? WHERE id = ?",
            [$pfpPath, $userId]
        ) > 0;
    }

    /** Update a user's language preference */
    public function updateUserLanguage(int $userId, string $language): bool {
        return $this->execute(
            "UPDATE Users SET language = ? WHERE id = ?",
            [$language, $userId]
        ) > 0;
    }


    // =========================================================
    // PROJECTS
    // =========================================================

    /** Create a new project, returns new project id */
    public function createProject(
        string $name,
        int    $clientUserId,
        string $description   = '',
        ?float $estimatedTime = null
    ): int {
        return $this->insert(
            "INSERT INTO Projects (name, user_id, status, progress_percent, creation_date, description, estimated_time, spent_time)
             VALUES (?, ?, 'pending', 0, NOW(), ?, ?, 0)",
            [$name, $clientUserId, $description, $estimatedTime]
        );
    }

    /** Get a single project by id */
    public function getProjectById(int $id): ?array {
        return $this->fetchOne(
            "SELECT * FROM Projects WHERE id = ?",
            [$id]
        );
    }

    /** Get all projects belonging to a client user */
    public function getProjectsByClient(int $clientUserId): array {
        return $this->fetchAll(
            "SELECT * FROM Projects WHERE user_id = ? ORDER BY creation_date DESC",
            [$clientUserId]
        );
    }

    /** Get all projects a user is a team member of */
    public function getProjectsByMember(int $userId): array {
        return $this->fetchAll(
            "SELECT p.* FROM Projects p
             JOIN Project_Team pt ON pt.project_id = p.id
             WHERE pt.user_id = ?
             ORDER BY p.creation_date DESC",
            [$userId]
        );
    }

    /** Update a project's status and/or progress */
    public function updateProjectProgress(int $projectId, int $progressPercent, string $status): bool {
        return $this->execute(
            "UPDATE Projects SET progress_percent = ?, status = ? WHERE id = ?",
            [$progressPercent, $status, $projectId]
        ) > 0;
    }

    /** Update a project's spent time */
    public function updateProjectSpentTime(int $projectId, float $spentTime): bool {
        return $this->execute(
            "UPDATE Projects SET spent_time = ? WHERE id = ?",
            [$spentTime, $projectId]
        ) > 0;
    }


    // =========================================================
    // PROJECT TEAM
    // =========================================================

    /** Add a user to a project team */
    public function addProjectMember(int $projectId, int $userId, string $role = 'worker'): bool {
        return $this->execute(
            "INSERT IGNORE INTO Project_Team (project_id, user_id, role) VALUES (?, ?, ?)",
            [$projectId, $userId, $role]
        ) > 0;
    }

    /** Remove a user from a project team */
    public function removeProjectMember(int $projectId, int $userId): bool {
        return $this->execute(
            "DELETE FROM Project_Team WHERE project_id = ? AND user_id = ?",
            [$projectId, $userId]
        ) > 0;
    }

    /** Get all team members of a project */
    public function getProjectTeam(int $projectId): array {
        return $this->fetchAll(
            "SELECT u.id, u.first_name, u.last_name, u.email, u.pfp, pt.role
             FROM Project_Team pt
             JOIN Users u ON u.id = pt.user_id
             WHERE pt.project_id = ?",
            [$projectId]
        );
    }


    // =========================================================
    // TICKETS
    // =========================================================

    /** Create a new ticket, returns new ticket id */
    public function createTicket(
        string $name,
        int    $projectId,
        string $priority      = 'medium',
        string $type          = 'task',
        string $description   = '',
        ?float $estimatedTime = null
    ): int {
        return $this->insert(
            "INSERT INTO Tickets (name, project_id, status, priority, type, description, estimated_time, spent_time)
             VALUES (?, ?, 'open', ?, ?, ?, ?, 0)",
            [$name, $projectId, $priority, $type, $description, $estimatedTime]
        );
    }

    /** Get a single ticket by id */
    public function getTicketById(int $id): ?array {
        return $this->fetchOne(
            "SELECT * FROM Tickets WHERE id = ?",
            [$id]
        );
    }

    /** Get all tickets for a project, optionally filtered by status */
    public function getTicketsByProject(int $projectId, ?string $status = null): array {
        if ($status !== null) {
            return $this->fetchAll(
                "SELECT * FROM Tickets WHERE project_id = ? AND status = ? ORDER BY priority DESC",
                [$projectId, $status]
            );
        }
        return $this->fetchAll(
            "SELECT * FROM Tickets WHERE project_id = ? ORDER BY priority DESC",
            [$projectId]
        );
    }

    /** Update a ticket's status */
    public function updateTicketStatus(int $ticketId, string $status): bool {
        return $this->execute(
            "UPDATE Tickets SET status = ? WHERE id = ?",
            [$status, $ticketId]
        ) > 0;
    }

    /** Update a ticket's spent time */
    public function updateTicketSpentTime(int $ticketId, float $spentTime): bool {
        return $this->execute(
            "UPDATE Tickets SET spent_time = ? WHERE id = ?",
            [$spentTime, $ticketId]
        ) > 0;
    }


    // =========================================================
    // TICKET WORKERS
    // =========================================================

    /** Assign a user to a ticket */
    public function assignTicketWorker(int $ticketId, int $userId): bool {
        return $this->execute(
            "INSERT IGNORE INTO Ticket_Worker (ticket_id, user_id) VALUES (?, ?)",
            [$ticketId, $userId]
        ) > 0;
    }

    /** Unassign a user from a ticket */
    public function unassignTicketWorker(int $ticketId, int $userId): bool {
        return $this->execute(
            "DELETE FROM Ticket_Worker WHERE ticket_id = ? AND user_id = ?",
            [$ticketId, $userId]
        ) > 0;
    }

    /** Get all workers assigned to a ticket */
    public function getTicketWorkers(int $ticketId): array {
        return $this->fetchAll(
            "SELECT u.id, u.first_name, u.last_name, u.email, u.pfp
             FROM Ticket_Worker tw
             JOIN Users u ON u.id = tw.user_id
             WHERE tw.ticket_id = ?",
            [$ticketId]
        );
    }

    /** Get all tickets assigned to a specific user */
    public function getTicketsByWorker(int $userId): array {
        return $this->fetchAll(
            "SELECT t.* FROM Tickets t
             JOIN Ticket_Worker tw ON tw.ticket_id = t.id
             WHERE tw.user_id = ?
             ORDER BY t.priority DESC",
            [$userId]
        );
    }


    // =========================================================
    // TICKET ATTACHMENTS
    // =========================================================

    /** Save an attachment record for a ticket, returns new attachment id */
    public function addTicketAttachment(int $ticketId, string $fileName): int {
        return $this->insert(
            "INSERT INTO Ticket_Attachments (ticket_id, file_name) VALUES (?, ?)",
            [$ticketId, $fileName]
        );
    }

    /** Get all attachments for a ticket */
    public function getTicketAttachments(int $ticketId): array {
        return $this->fetchAll(
            "SELECT * FROM Ticket_Attachments WHERE ticket_id = ?",
            [$ticketId]
        );
    }

    /** Delete an attachment by id */
    public function deleteTicketAttachment(int $attachmentId): bool {
        return $this->execute(
            "DELETE FROM Ticket_Attachments WHERE id = ?",
            [$attachmentId]
        ) > 0;
    }
}
