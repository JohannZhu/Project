<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Job Hub</title>
        <link rel="stylesheet" href="main.css" />
        <?php
            function runWithSqlConnection($func) {
                $conn = mysqli_connect("localhost", "root", "", "ESOF-5334"); // server name, user name, pw, db name
                $result = $func($conn);
                $conn->close();
                return $result;
            }
        ?>
    </head>
    <body>
        <div class="container">
            <header>
                <img id="logo" src="jobhub.png" height="50px">
            </header>
            <nav>
                <hr>
                <p>3 results</p>
            </nav>
            <main>
                <?php
                    function generateHtml($sql_con) {
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
                            LIMIT 10;");

                        while ($row = $result->fetch_assoc()) {
                            $html = str_replace("<LogoUrl />", $row["LogoUrl"], $template);
                            $html = str_replace("<JobTitle />", $row["JobTitle"], $html);
                            $html = str_replace("<CompanyName />", $row["CompanyName"], $html);
                            $html = str_replace("<JobLocation />", $row["JobLocation"], $html);
                            $html = str_replace("<ApplyUrl />", $row["ApplyUrl"], $html);
                            $html = str_replace("<JobDesHtml />", $row["JobDesHtml"], $html);
                            echo $html;
                        }
                    }

                    runWithSqlConnection('generateHtml')
                ?>
                <!-- <section class="single-listing">
                    <hr>
                    <div class="job-header-logo">
                        <img src="06010-medium.jpg" width="100px" height="100px">
                    </div>
                    <div class="job-header-text">
                        <h3>Job Title</h3>
                        <p>Company Name</p>
                        <p>Salary</p>
                        <p>Location</p>
                        <a href="#">Email</a>
                    </div>
                    <section class="single-listing-popup">
                        <div class="job-header">
                            <div class="job-header-logo">
                                <img src="06010-medium.jpg" width="100px" height="100px">
                            </div>
                            <div class="job-header-text">
                                <h3>Job Title</h3>
                                <p>Company Name</p>
                                <p>Salary</p>
                                <p>Location</p>
                                <p>Email</p>
                            </div>
                        </div>
                        <h3>Job Requirements</h3>
                        <ul>
                            <li>Req I</li>
                            <li>Req II</li>
                            <li>Req III</li>
                        </ul>
                        <h3>Job Description</h3>
                        <p>Detail Description</p>
                        <h3>Benefit Package</h3>
                        <p>Detail Benefit</p>
                    </section>
                </section> -->
            </main>
            <footer>
                <hr />
                <hr />
            </footer>
        </div>
    </body>
</html>