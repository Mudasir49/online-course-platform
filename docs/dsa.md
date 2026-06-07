# DSA Implementation in Mini-Coursera

## 1. Lecture Sequence as DAG (Directed Acyclic Graph)
**File**: `backend/dsa/Graph.php`

### Concept
Lectures are modeled as nodes in a DAG.
- **Node**: Lecture
- **Edge**: Dependency (Lecture A -> Lecture B implies A must be finished before B)

### Complexity
- **Storage**: Adjacency List uses $O(V + E)$ space.
- **Next Unlocked Lecture**: We use a modified BFS (Breadth-First Search).
  - Time Complexity: $O(V + E)$ in the worst case to traverse the graph.
  - Verification: Ensures no cycles and respects topological order.

### Why DAG?
While our current courses are linear lists ($1 \to 2 \to 3$), a DAG allows for branching paths (e.g., complete "Basic Math" OR "Basic Logic" to unlock "Algorithms"). This makes the system extensible.

---

## 2. HashMap for User Progress (O(1) Access)
**Usage**: Everywhere in PHP (`$progressMap` in `course.php`)

### Concept
PHP's associative arrays are implemented as Hash Tables.
We store completed lecture IDs as keys: `$progress['lecture_id'] = true`.

### Complexity
- **Lookup**: $O(1)$ average case.
- **Insertion**: $O(1)$.
- **Why**: Checking `isUnlocked()` inside a loop of lectures would be $O(N)$ if we used a list search. With a Map, checking 6 lectures is $O(6 \times 1) = O(1)$.

---

## 3. Max-Heap for Leaderboard
**File**: `backend/dsa/Leaderboard.php`

### Concept
We use `SplPriorityQueue` (Standard PHP Library) which implements a Max-Heap.
Users are inserted with their total score as priority.

### Complexity
- **Insert**: $O(\log N)$
- **Extract Max**: $O(\log N)$
- **Get Top K**: $O(K \log N)$

### Why Heap?
Sorting all users by score every time we view the leaderboard would be $O(N \log N)$.
A Heap allows us to maintain a priority queue where getting the top element is efficient. For a live coding platform "Top 10" or "Recent High Scores", a heap structure is ideal for streaming data.

---

## 4. Usage in Project
To see these in action, visit `progress.php` (once implemented), which visualizes the DAG status and the Top Scores using these classes.
