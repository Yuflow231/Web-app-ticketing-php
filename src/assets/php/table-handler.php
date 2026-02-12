<?php
    // Import debug handler
    require_once("../../assets/php/debug-handler.php");
    $debugHandler = DebugHandler::getInstance();

    $count = 1;
    function setBadgeColor(string $tag): void {
        global $count;
        $tagLower = strtolower($tag);
        $color = "blue"; // Default

        if ($tagLower === "in progress" || $tagLower === "completed" || $tagLower === "high") {
            $color = "green";
        } elseif ($tagLower === "on hold" || $tagLower === "medium") {
            $color = "orange";
        } elseif ($tagLower === "closed" || $tagLower === "low") {
            $color = "red";
        }

        echo $color;

        $debug = DebugHandler::getInstance();
        $debug->addInfoRight("Badge " . $count. ": ". $tag . " ", $color);
    }

    function isSelected(string $paramName, string $tag): void {
        // Check if the specific GET parameter exists (e.g., 'status' or 'priority')
        $currentValue = strtolower(htmlspecialchars($_GET[$paramName] ?? ''));
        $tagLower = strtolower($tag);

        if ($currentValue === $tagLower) {
            echo "selected";
        }
    }

    function isFiltered(array $row, array $filterKeys): bool {
        foreach ($filterKeys as $key) {
            $filterValue = strtolower(htmlspecialchars($_GET[$key]));

            if ($filterValue === "all" || $filterValue === "") {
                continue;
            }

            $rowValue = strtolower($row[$key] ?? '');

            if ($filterValue !== $rowValue) {
                return false;
            }
        }
        return true;
    }

    function addCount():void{
        global $count;
        $count++;
    }
?>