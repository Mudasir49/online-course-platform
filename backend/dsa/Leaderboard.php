<?php
// backend/dsa/Leaderboard.php

class LeaderboardHeap extends SplPriorityQueue {
    public function compare($score1, $score2) {
        // Max Heap: Return positive if score1 > score2
        return $score1 - $score2;
    }
}

// Usage Helper
class LeaderboardSystem {
    private $heap;

    public function __construct() {
        $this->heap = new LeaderboardHeap();
    }

    public function addScore($user, $score) {
        // SplPriorityQueue stores 'data' and 'priority'
        // We store User Info as data, Score as priority
        $this->heap->insert($user, $score);
    }

    public function getTop($k) {
        // Extract top K
        // Note: Extracting removes from heap. Clone if needed to preserve.
        $top = [];
        $cloned = clone $this->heap;
        $cloned->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        
        for ($i = 0; $i < $k; $i++) {
            if ($cloned->valid()) {
                $node = $cloned->extract(); // ['data' => user, 'priority' => score]
                $top[] = $node;
            } else {
                break;
            }
        }
        return $top;
    }
}
?>
