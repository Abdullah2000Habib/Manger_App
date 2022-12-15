<?php
use Aws\CloudWatch\CloudWatchClient;
use Aws\Exception\AwsException;
class cloudWatch
{
    function putMetricData($cloudWatchClient, $cloudWatchRegion, $namespace,
                           $metricData)
    {
        try {
            $result = $cloudWatchClient->putMetricData([
                'Namespace' => $namespace,
                'MetricData' => $metricData
            ]);

            if (isset($result['@metadata']['effectiveUri'])) {
                if ($result['@metadata']['effectiveUri'] ==
                    'https://monitoring.' . $cloudWatchRegion . '.amazonaws.com') {
                    return 'Successfully published datapoint(s).';
                } else {
                    return 'Could not publish datapoint(s).';
                }
            } else {
                return 'Error: Could not publish datapoint(s).';
            }
        } catch (AwsException $e) {
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }
    function getStatisticsLastxMinuates($cloudWatchClient,$nameSpace,$time)
    {
        $requiredMetrices = array();
        try {
            $result = $cloudWatchClient->listMetrics();
            if (isset($result['@metadata']['effectiveUri'])) {
                if ((isset($result['Metrics'])) and
                    (count($result['Metrics']) > 0)) {
                    foreach ($result['Metrics'] as $metric) {
                        if($metric["Namespace"] == $nameSpace){
                            $metricTime = strtotime($metric["Dimensions"][3]["Value"]);
                            $time = strtotime($time);
                            if($metricTime >= $time){
                                $requiredMetrices[] = $metric;
                            }
                        }
                    }
                }
            }
            return $requiredMetrices;

        } catch (AwsException $e) {
            return 'Error: ' . $e->getAwsErrorMessage();
        }
        //return 0;
    }
    public function putMode($mode){
        $cloudWatchRegion = 'us-east-1';
        $cloudWatchClient = $this->getCloudWatchClient();
        $namespace = 'MyNamespace';
        $timeStamp = getdate();
        $metricData = [
            [
                'MetricName' => 'mem-cache',
                'Timestamp' => $timeStamp[0], // 11 May 2020, 20:26:58 UTC.
                'Dimensions' => [
                    [
                        'Name' => 'mode',
                        'Value' => $mode

                    ],
                    [
                        'Name' => 'time',
                        'Value' => $timeStamp[0]
                    ]
                ],
                'Unit' => 'Count',
                'Value' => 1
            ]
        ];
        $this->putMetricData($cloudWatchClient,$cloudWatchRegion,"MODE",$metricData);
    }

    public function saveAutoConfig($maxMissRate,$minMissRate)
    {
        $cloudWatchRegion = 'us-east-1';
        $cloudWatchClient = $this->getCloudWatchClient();

        $namespace = 'MyNamespace';
        $timeStamp = getdate();
        $metricData = [
            [
                'MetricName' => 'mem-cache',
                'Timestamp' => $timeStamp[0], // 11 May 2020, 20:26:58 UTC.
                'Dimensions' => [
                    [
                        'Name' => 'minMissRate',
                        'Value' => $minMissRate

                    ],
                    [
                        'Name' => 'maxMissRate',
                        'Value' => $maxMissRate
                    ],
                    [
                        'Name'=>'time',
                        'Value'=>$timeStamp[0]
                    ]
                ],
                'Unit' => 'Count',
                'Value' => 1
            ]
        ];
        $this->putMetricData($cloudWatchClient,$cloudWatchRegion,"AUTO",$metricData);
    }
    /*public function getMaxMissRate(){
        echo "Hi";
        $namespace = "AUTO";

        $cloudWatchClient =  $this->getCloudWatchClient();
        var_dump( $this->listMetrics($cloudWatchClient,"MyNamespace","NumberOfRequests"));
    }*/

    public function getMinMissRate()
    {
        return false;
    }

    public function getMode()
    {
        return false;
    }

    public function getCloudWatchStatistics()
    {
        $result = array();
        $time = getdate();
        $time = date('m/d/Y h:i',strtotime('-29 minutes',$time[0]));
        $result["startTime"] = $time;
        $cloudWatchClient = $this->getCloudWatchClient();
        $cloudWatchStatistics = $this->getStatisticsLastxMinuates($cloudWatchClient,"Statistics",$time);
        $numberOfWorkerStatistics = $this->getNumberOfWorkersStatistics($cloudWatchClient,"Pool",$time);
        for($i = 0;$i < 30;$i++){
            $count = 0;
            foreach ($cloudWatchStatistics as $metric){
                $count++;
                if($metric["Dimensions"][3]["Value"] == $time){
                    foreach ($metric["Dimensions"] as $diemension){
                        $name = $diemension["Name"];
                        if($name == "time"){
                            continue;
                        }
                        $value = $diemension["Value"];
                        if(!isset($result[$time][$name])){
                            $result[$time][$name] = 0;
                        }
                        $result[$time][$name] += $value;
                        $result[$time][$name] /= $count;

                    }
                }
            }
            $count = 0;
            foreach ($numberOfWorkerStatistics as $metric){
                $count++;
                if($metric["Dimensions"][1]["Value"] == $time){
                    foreach ($metric["Dimensions"] as $diemension){
                        $name = $diemension["Name"];
                        if($name == "time"){
                            continue;
                        }
                        $value = $diemension["Value"];
                        if(!isset($result[$time][$name])){
                            $result[$time][$name] = 0;
                        }
                        $result[$time][$name] += $value;
                        $result[$time][$name] /= $count;

                    }
                }
            }
            $time = date('m/d/Y h:i', strtotime($time. ' +1 minutes'));
        }

        return json_encode($result);
    }

    private function getCloudWatchClient()
    {
        $cloudWatchRegion = 'us-east-1';
        $cloudWatchClient = new CloudWatchClient([
            'credentials'=>[
                'key'    => IAM_KEY,
                'secret' => IAM_SECRET,

            ],
            'region' => $cloudWatchRegion,
            'version' => '2010-08-01',
        ]);
        return $cloudWatchClient;
    }
    public function saveNumberOfWorkers($numberOfWorkers){
        $cloudWatchRegion = 'us-east-1';
        $cloudWatchClient = $this->getCloudWatchClient();
        $namespace = 'Pool';
        $timeStamp = getdate();
        $time = date('m/d/Y h:i',$timeStamp[0]);
        $metricData = [
            [
                'MetricName' => 'mem-cache',
                'Timestamp' => $timeStamp[0], // 11 May 2020, 20:26:58 UTC.
                'Dimensions' => [
                    [
                        'Name' => 'numberOfWorkers',
                        'Value' => $numberOfWorkers

                    ],
                    [
                        'Name' => 'time',
                        'Value' => $time
                    ]
                ],
                'Unit' => 'Count',
                'Value' => 1
            ]
        ];
        $this->putMetricData($cloudWatchClient,$cloudWatchRegion,$namespace,$metricData);
    }
    public function getNumberOfWorkersStatistics($cloudWatchClient,$namespace,$time){
        $requiredMetrices = array();
        try {
            $result = $cloudWatchClient->listMetrics();
            if (isset($result['@metadata']['effectiveUri'])) {
                if ((isset($result['Metrics'])) and
                    (count($result['Metrics']) > 0)) {
                    foreach ($result['Metrics'] as $metric) {
                        if($metric["Namespace"] == $namespace){
                            $metricTime = strtotime($metric["Dimensions"][1]["Value"]);
                            $time = strtotime($time);
                            if($metricTime >= $time){
                                $requiredMetrices[] = $metric;
                            }
                        }
                    }
                }
            }
            return $requiredMetrices;

        } catch (AwsException $e) {
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }

}