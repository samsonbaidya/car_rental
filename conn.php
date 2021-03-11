<?php
	$conn = oci_connect('samson', '1342', 'localhost/XE');
    function execute($query)
    {
        //echo $query;
        global $conn;
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $res = oci_parse($conn,$query);
        oci_execute($res);
        return $res;

    }

    function getPlSqlData($sql)
    {
        global $conn;
        SetServerOutput($conn, true);
        $s = oci_parse($conn, $sql);
        oci_execute($s);
        return GetDbmsOutput($conn);
    }

    function SetServerOutput($c, $p)
    {
        if ($p)
        $s = "BEGIN DBMS_OUTPUT.ENABLE(NULL); END;";
        else
        $s = "BEGIN DBMS_OUTPUT.DISABLE(); END;";
        $s = oci_parse($c, $s);
        $r = oci_execute($s);
        oci_free_statement($s);
        return $r;
    }

    // Returns an array of dbms_output lines, or false.
    function GetDbmsOutput($c)
    {
        $res = false;
        $s = oci_parse($c, "BEGIN DBMS_OUTPUT.GET_LINE(:LN, :ST); END;");
        if (oci_bind_by_name($s, ":LN", $ln, 255) &&
        oci_bind_by_name($s, ":ST", $st)) {
        $res = array();
        while (($succ = oci_execute($s)) && !$st)
        $res[] = $ln;
            if (!$succ)
            $res = false;
            }
        oci_free_statement($s);
        return $res;
    }

    function get($res)
    {
       return oci_fetch_array($res, OCI_ASSOC+OCI_RETURN_NULLS);
    }

?>
