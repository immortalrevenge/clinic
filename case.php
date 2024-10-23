<?php
// Database connection parameters
include 'conn.php';

// SQL query to get health cases categorized by month and case type
$sql = "WITH months AS (
    SELECT 1 AS month_num, 'January' AS month_name UNION ALL
    SELECT 2, 'February' UNION ALL
    SELECT 3, 'March' UNION ALL
    SELECT 4, 'April' UNION ALL
    SELECT 5, 'May' UNION ALL
    SELECT 6, 'June' UNION ALL
    SELECT 7, 'July' UNION ALL
    SELECT 8, 'August' UNION ALL
    SELECT 9, 'September' UNION ALL
    SELECT 10, 'October' UNION ALL
    SELECT 11, 'November' UNION ALL
    SELECT 12, 'December'
)
SELECT 
    m.month_name,
    c.case_name,
    IFNULL(COUNT(hc.prescription_id), 0) AS total_cases
FROM 
    months m
CROSS JOIN 
    cases_tbl c
LEFT JOIN 
    prescription_tbl hc ON MONTH(hc.dOprescription) = m.month_num 
    AND YEAR(hc.dOprescription) = 2024 
    AND hc.complaint = c.case_name
GROUP BY 
    m.month_name, c.case_name
ORDER BY 
    m.month_num, c.case_id;";

// Execute the query
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("SQL Error: " . $conn->error);
}

// Prepare to structure the report
$reportData = [];
$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
$totalCases = array_fill(0, 12, 0); // Initialize total cases for each month

// Process the results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $caseName = $row['case_name'];
        $monthName = $row['month_name'];
        $total = $row['total_cases'];

        // Populate report data with total cases under each case type and month
        $reportData[$caseName][$monthName] = $total;

        // Find month index for total case calculation
        $monthIndex = array_search($monthName, $months);
        if ($monthIndex !== false) {
            $totalCases[$monthIndex] += $total; // Increment total cases for the month
        }
    }
} else {
    echo "No records found.";
}

// Close the connection
$conn->close();

// Generate the report
echo "<h1>Health Cases Report by Month</h1>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Case Type</th>";

// Print month headers
foreach ($months as $month) {
    echo "<th>$month</th>";
}
echo "</tr>";

// Print case types and their corresponding cases
foreach ($reportData as $caseType => $cases) {
    echo "<tr><td>$caseType</td>";
    foreach ($months as $month) {
        // Display the number of cases or 0 if not present
        $count = isset($cases[$month]) ? $cases[$month] : 0;
        echo "<td>$count</td>";
    }
    echo "</tr>";
}

// Add total cases row
echo "<tr><td><strong>Total Cases</strong></td>";
foreach ($totalCases as $total) {
    echo "<td>$total</td>";
}
echo "</tr>";

echo "</table>";
?>
