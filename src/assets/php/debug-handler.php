<?php
/**
 * DebugHandler - Utility class for managing debug mode across the application
 * 
 * Features:
 * - Centralized debug state management
 * - Flexible debug panel rendering
 * 
 * Usage:
 * require_once("path/to/debug-handler.php");
 * $debugHandler = DebugHandler::getInstance();
 */
class DebugHandler {
    private static ?DebugHandler $instance = null;
    private bool $enabled;
    private string $debugParam;
    private array $debugInfo;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        $this->enabled = isset($_GET["debug"]) && $_GET["debug"] == "1";
        $this->debugParam = $this->enabled ? "?debug=1" : "";
        $this->debugInfo = [];

        if ($this->enabled) {
            $this->collectDefaultInfo();
        }
    }

    /**
     * Get the instance
     * @return DebugHandler
     */
    public static function getInstance(): DebugHandler {
        if (self::$instance === null) {
            self::$instance = new DebugHandler();
        }
        return self::$instance;
    }
    
    /**
     * Check if debug mode is enabled
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->enabled;
    }
    
    /**
     * Get the debug parameter string for URLs
     * @return string "?debug=1" or ""
     */
    public function getDebugParam(): string {
        return $this->debugParam;
    }
    
    /**
     * Add custom debug information
     * @param string $key The label for the debug info
     * @param mixed $value The value to display
     */
    public function addInfo(string $key, $value): void {
        $this->debugInfo[$key] = $value;
    }
    
    /**
     * Collect default debug information
     */
    private function collectDefaultInfo(): void {
        $this->debugInfo['Full Path'] = $_SERVER['PHP_SELF'];
    }
    
    /**
     * Add GET parameters to debug panel
     */
    public function addGetParams(): void {
        if (!$this->enabled) return;
        
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key !== 'debug') {
                    $this->debugInfo["GET: {$key}"] = $value;
                }
            }
        }
    }
    
    /**
     * Add POST parameters to debug panel
     */
    public function addPostParams(): void {
        if (!$this->enabled) return;
        
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                // Don't display sensitive fields
                if (in_array(strtolower($key), ['password', 'pwd', 'pass', 'token', 'secret'])) {
                    $this->debugInfo["POST: {$key}"] = '[HIDDEN]';
                } else {
                    $this->debugInfo["POST: {$key}"] = is_array($value) ? json_encode($value) : $value;
                }
            }
        }
    }
    
    /**
     * Render the debug panel HTML
     */
    public function renderPanel():void {
        // skip the render when not enabled
        if (!$this->enabled) {
            return;
        }
        
        $html = "<div class='debug-panel'>\n";
        
        foreach ($this->debugInfo as $key => $value) {
            $html .= "  <p class='debug-element'><strong>{$key}:</strong> {$value}</p>\n";
        }
        
        $html .= "</div>\n";
        echo $html;

    }
    /**
     * Clear all debug info
     */
    public function clearInfo(): void {
        $this->debugInfo = [];
    }
}
