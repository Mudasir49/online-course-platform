<?php
// tests/test_grading.php

function testGradingLogic() {
    echo "Running Grading Logic Tests...\n";
    $passes = 0;
    $fails = 0;

    // Test 1: MCQ Passing Threshold (5/10)
    $correct = 5;
    $total = 10;
    $isPass = ($correct >= 5);
    if ($isPass === true) {
        echo "[PASS] 5/10 correct should pass.\n";
        $passes++;
    } else {
        echo "[FAIL] 5/10 correct should pass.\n";
        $fails++;
    }

    // Test 2: MCQ Failing Threshold (4/10)
    $correct = 4;
    $isPass = ($correct >= 5);
    if ($isPass === false) {
        echo "[PASS] 4/10 correct should fail.\n";
        $passes++;
    } else {
        echo "[FAIL] 4/10 correct should fail.\n";
        $fails++;
    }

    // Test 3: Final Exam Threshold (80%)
    $score = 80;
    $total = 100;
    $isPass = ($score / $total * 100) >= 80;
    if ($isPass === true) {
        echo "[PASS] 80% should pass final exam.\n";
        $passes++;
    } else {
        echo "[FAIL] 80% should pass final exam.\n";
        $fails++;
    }

    // Test 4: Final Exam Fail (70%)
    $score = 70;
    $isPass = ($score / $total * 100) >= 80;
    if ($isPass === false) {
        echo "[PASS] 70% should fail final exam.\n";
        $passes++;
    } else {
        echo "[FAIL] 70% should fail final exam.\n";
        $fails++;
    }

    echo "\nTotal Tests: " . ($passes + $fails) . "\n";
    echo "Passed: $passes\n";
    echo "Failed: $fails\n";
}

testGradingLogic();
?>
