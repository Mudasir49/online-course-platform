<?php
// backend/dsa/Graph.php

class LectureGraph {
    private $adjList = [];
    private $nodes = []; // id => title

    // Add a lecture node
    public function addLecture($id, $title) {
        if (!isset($this->adjList[$id])) {
            $this->adjList[$id] = [];
            $this->nodes[$id] = $title;
        }
    }

    // Add a directed edge (prerequisite -> next)
    // In our linear course, 1 -> 2 -> 3
    public function addDependency($fromId, $toId) {
        $this->adjList[$fromId][] = $toId;
    }

    // BFS to find the next unlockable lecture
    // completedIds: array of completed lecture IDs
    public function getNextUnlocked($completedIds) {
        // Source node (assuming 1st added is source, or find one with in-degree 0)
        // For linear 1->2->3, we can iterate.
        // A generic DAG approach:
        
        $unlocked = []; // Lectures accessible
        
        // In our specific case, the first lecture is always unlocked.
        // If a node is completed, its children are unlocked.
        
        // We need to know the 'start' nodes (roots)
        // Simplification: We iterate all nodes. If a node has NO incomplete prerequisites, it's unlocked.
        
        foreach ($this->nodes as $id => $title) {
            // Check if already completed
            if (in_array($id, $completedIds)) continue;

            // Check if prerequisites are met.
            // But strict DAG traversal checks parents. 
            // My adjList is parent -> child.
            // I need reverse lookup (Dependencies) to check efficiently, 
            // OR I traverse from roots.
            
            // Let's build an in-degree map or parent map for this check
            // For this specific 'next unlocked' feature:
            // logic: If I haven't done it, can I do it?
            // Yes, if all parents are done.
            
            // Let's assume linear for this method's simplicity in demo,
            // but providing the BFS as requested.
        }
        
        // Correct BFS Traversal to find *reachability*
        // Queue: [StartNode]
        // If StartNode in completed, add neighbors to Queue.
        // Else, StartNode is the "Next Unlocked".
        
        // Find roots (nodes with no incoming edges) -- O(V+E)
        $inDegree = array_fill_keys(array_keys($this->nodes), 0);
        foreach ($this->adjList as $u => $neighbors) {
            foreach ($neighbors as $v) {
                if (!isset($inDegree[$v])) $inDegree[$v] = 0;
                $inDegree[$v]++;
            }
        }
        
        $queue = new SplQueue();
        foreach ($inDegree as $node => $deg) {
            if ($deg === 0) $queue->enqueue($node);
        }
        
        $next = null;
        
        // BFS
        $traversed = [];
        while (!$queue->isEmpty()) {
            $u = $queue->dequeue();
            if (isset($traversed[$u])) continue;
            $traversed[$u] = true;
            
            if (!in_array($u, $completedIds)) {
                return $u; // Found the first incomplete accessible node
            }
            
            // If completed, explore neighbors
            if (isset($this->adjList[$u])) {
                foreach ($this->adjList[$u] as $v) {
                    $queue->enqueue($v);
                }
            }
        }
        
        return null; // All done
    }
}
?>
