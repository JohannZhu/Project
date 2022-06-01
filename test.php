<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <table>
            <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Email</th>
            </tr>
            <?php
                $conn = mysqli_connect("localhost", "root", "", "ESOF-5334"); // server name, user name, pw, db name
                $sql = "SELECT * from testa";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["Name"] . "</td><td>" . $row["ID"] . "</td><td>" . $row["Email"] . "</td></tr>"
                }
                $conn->close()
            ?>
        </table>
    </body>
</html>