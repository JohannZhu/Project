<?php
    $template = "
        <section class=\"single-listing\">
            <hr>
            <div class=\"job-header-logo\">
                <img src=\"<LogoUrl />\" width=\"100px\" height=\"100px\">
            </div>
            <div class=\"job-header-text\">
                <h3><JobTitle /></h3>
                <p><CompanyName /></p>
                <p><JobLocation /></p>
                <a href=\"<ApplyUrl />\">Apply Url</a>
                <p><GoodFor /></p>
            </div>
            <section class=\"single-listing-popup\">
                <div class=\"job-header\">
                    <div class=\"job-header-logo\">
                        <img src=\"<LogoUrl />\" width=\"100px\" height=\"100px\">
                    </div>
                    <div class=\"job-header-text\">
                        <h3><JobTitle /></h3>
                        <p><CompanyName /></p>
                        <p><JobLocation /></p>
                        <a href=\"<ApplyUrl />\">Apply Url</a>
                        <p><GoodFor /></p>
                    </div>
                </div>
                <JobDesHtml />
            </section>
        </section>";

    $where_clauses = Sql_Refine();
    $orderBy_clauses = Sql_OrderBy();
    $limit_clauses = Sql_limit();
    $query = "
        SELECT
            j.Title AS JobTitle,
            j.City AS JobLocation,
            j.Post_Date AS PostDate,
            j.Exp_Date AS ExpireDate,
            j.Apply_Url AS ApplyUrl,
            j.Html_Description AS JobDesHtml,
            c.Name AS CompanyName,
            c.Logo AS LogoUrl
        FROM jobs AS j
        INNER JOIN companies AS c ON j.Company_ID > 1 and j.Company_ID = c.ID
        $where_clauses
        $orderBy_clauses
        $limit_clauses;";
    Write_log("Query:\n$query");

    $sql_con = mysqli_connect("localhost", "root", "", "ESOF-5334"); // server name, user name, pw, db name
    $result = $sql_con->query($query) or die("ERROR: $sql_con->error Query: $query");
    while ($row = $result->fetch_assoc()) {
        $html = str_replace("<LogoUrl />", $row["LogoUrl"], $template);
        $html = str_replace("<JobTitle />", $row["JobTitle"], $html);
        $html = str_replace("<CompanyName />", $row["CompanyName"], $html);
        $html = str_replace("<JobLocation />", $row["JobLocation"], $html);
        $html = str_replace("<GoodFor />", "Post Good For: " . $row["PostDate"] . " => " . $row["ExpireDate"], $html);
        $html = str_replace("<ApplyUrl />", $row["ApplyUrl"], $html);
        $html = str_replace("<JobDesHtml />", $row["JobDesHtml"], $html);
        echo $html;
    }
    $sql_con->close();

    function GetCurrentNY() {
        return gmdate("Y-m-d\TH:i:s\Z");
    }

    function Write_log($msg) {
        $log_file = fopen("./errors.log", "a");
        $timestamp = GetCurrentNY();
        fwrite($log_file, "[$timestamp] [loadmore.php] $msg\n");
        fclose($log_file);
    }

    function Sql_limit() {
        $first_load_size = 30;
        $continue_load_size = 10;

        $index = $_GET['index'];
        $load_size = $first_load_size;
        $load_start = 0;
        if ($index > 0) {
            $load_size = $continue_load_size;
            $load_start = $first_load_size + ($index - 1) * $continue_load_size;
        }
        return "LIMIT $load_start, $load_size";
    }

    function Sql_OrderBy() {
        $result = 'ORDER BY';
        $orderBy = $_GET['orderBy'];
        if ($orderBy === 'Post Date') {
            $result = "$result j.Post_Date";
        } elseif ($orderBy === 'Expire Date') {
            $result = "$result j.Exp_Date";
        } else {
            $result = "";
        }
        return $result;
    }

    function Sql_Refine() {
        $where_clauses = array();

        if (isset($_GET['mustHaveInTitle'])) {
            foreach (explode(" ", $_GET['mustHaveInTitle']) as $term) {
                $where_clauses[] = "j.Title LIKE '%$term%'";
            }
        }

        if (count($where_clauses) > 0) {
            return "WHERE " . implode(" AND ", $where_clauses);
        } else {
            return "";
        }
    }
?>