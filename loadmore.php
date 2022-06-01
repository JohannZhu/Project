<?php
    $first_load_size = 30;
    $continue_load_size = 10;
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
                    </div>
                </div>
                <JobDesHtml />
            </section>
        </section>";

    $sql_con = mysqli_connect("localhost", "root", "", "ESOF-5334"); // server name, user name, pw, db name

    $index = $_GET['index'];
    $load_size = $first_load_size;
    $load_start = 0;
    if ($index > 0) {
        $load_size = $continue_load_size;
        $load_start = $first_load_size + ($index - 1) * $continue_load_size;
    }

    $result = $sql_con->query("
        SELECT
            j.Title AS JobTitle,
            j.City AS JobLocation,
            j.Apply_Url AS ApplyUrl,
            j.Html_Description AS JobDesHtml,
            c.Name AS CompanyName,
            c.Logo AS LogoUrl
        FROM jobs AS j
        INNER JOIN companies AS c ON j.Company_ID > 1 and j.Company_ID = c.ID
        LIMIT $load_start, $load_size;");

    while ($row = $result->fetch_assoc()) {
        $html = str_replace("<LogoUrl />", $row["LogoUrl"], $template);
        $html = str_replace("<JobTitle />", $row["JobTitle"], $html);
        $html = str_replace("<CompanyName />", $row["CompanyName"], $html);
        $html = str_replace("<JobLocation />", $row["JobLocation"], $html);
        $html = str_replace("<ApplyUrl />", $row["ApplyUrl"], $html);
        $html = str_replace("<JobDesHtml />", $row["JobDesHtml"], $html);
        echo $html;
    }

    $sql_con->close();
?>