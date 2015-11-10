<?php

class Catchpoint_Metrics
{
    public function getJson($request, $result)
    {
        foreach ($result as $r) {
            $a[] = array(0, $r->$request);
        }

        return json_encode($a, JSON_NUMERIC_CHECK);
    }

    public function getJson2($request, $result)
    {
        foreach ($result as $r) {
            $a[] = array($r->$request,$r->webpage_response);
        }

        return json_encode($a, JSON_NUMERIC_CHECK);
    }

    public function getJson3($result)
    {
        foreach ($result as $r) {
            $a[] = array($r->items, $r->bytes);
        }

        return json_encode($a, JSON_NUMERIC_CHECK);
    }
}
