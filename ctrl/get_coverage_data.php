<?php
include "../includes/common.php";
header('Content-Type: application/json');



// Get data for charts
$data = [
    'state_coverage' => [],
    'county_coverage' => [],
    'provider_distribution' => []
];

// 1. State Coverage Chart Data
$stateQuery = "SELECT s.state_name, COUNT(DISTINCT c.iproviderID) as provider_count 
              FROM coverages c
              JOIN states s ON FIND_IN_SET(s.state_id, REPLACE(c.vStates, '|', ','))
              GROUP BY s.state_name
              ORDER BY provider_count DESC
              LIMIT 10";
$stateResult = sql_query($stateQuery);
while ($row = sql_fetch_assoc($stateResult)) {
    $data['state_coverage'][] = $row;
}

// 2. County Coverage Chart Data
$countyQuery = "SELECT cnt.county_name, COUNT(DISTINCT c.iproviderID) as provider_count 
               FROM coverages c
               JOIN counties cnt ON FIND_IN_SET(cnt.county_id, REPLACE(c.vCounties, '|', ','))
               GROUP BY cnt.county_name
               ORDER BY provider_count DESC
               LIMIT 10";
$countyResult = sql_query($countyQuery);
while ($row = sql_fetch_assoc($countyResult)) {
    $data['county_coverage'][] = $row;
}

// 3. Provider Distribution Chart Data
$providerQuery = "SELECT 
                    COUNT(*) as total_providers,
                    SUM(CASE WHEN cStatus = 'A' THEN 1 ELSE 0 END) as active_providers,
                    SUM(CASE WHEN cStatus = 'I' THEN 1 ELSE 0 END) as inactive_providers
                 FROM service_providers";
$providerResult = sql_query($providerQuery);
//$data['provider_distribution'] = sql_fetch_assoc($providerResult);

sql_close();
echo json_encode($data);
