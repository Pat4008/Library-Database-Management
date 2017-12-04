<?php
$num = $_GET['num'];
$val = 1;//$_POST['value'];
DEFINE ('DB_USER', 'user');
DEFINE ('DB_PASSWORD', 'password');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'library');
$servername='localhost';
$username='user';
$password='password';

$con = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
/*
$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
*/

/*
$sql = "select *, from book";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "printing";
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row['ID']. " - cnt: " . $row['Amount']. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
*/

if($con){
    echo "<!DOCTYPE html> <html><head><style> table, th, td {
    border: 1px solid black;}</style></head><body><table style=\"width:100%;\">";
    if($num=='1'){

        $getval = "select a.BranchID, max(a.cnt) as Amount 
        from (select l.BranchID, count(*) as cnt from BOOK_LOANS as l group by BranchID) as a;";
        $res=@mysqli_query($con,$getval);
        $oindex="CREATE INDEX temp on BOOK_LOANS (BranchID)";
        $rind=@mysqli_query($con,$oindex);
        $cindex="drop index temp on BOOK_LOANS;";
        $getval2="select * from branchmax";
        $otable="create temporary table TA as select a.BranchID, max(a.cnt) as Amount
        from (select l.BranchID, count(*) as cnt from BOOK_LOANS as l group by BranchID) as a;";
        $ctable="Drop table TA;";
        $getval3="select * from TA";
        $res3=@mysqli_query($con,$getval2);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>Original</th></tr>';
        if($res){
            echo '<tr><th>Branch ID</th><th>Amount</th></tr>';
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
            }
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            echo '<tr><th>Branch ID</th><th>Amount</th></tr>';
            if($res){
                echo '<tr><th>Branch ID</th><th>Amount</th></tr>';
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                }
            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ View</th></tr>';
        if($res3){
            echo '<tr><th>Branch ID</th><th>Amount</th></tr>';
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
            }
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo '<tr><th>Branch ID</th><th>Amount</th></tr>';
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                }
            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==2){//not don

        $getval = "SELECT b.CardNo, b.TimesBorrowed
          FROM (SELECT COUNT(BookID) TimesBorrowed, CardNo FROM Book_Loans WHERE Year(DateOut) =".$_GET['iyr']."
          AND Month(DateOut) = ".$_GET['im']." GROUP BY CardNo) as b ORDER BY TimesBorrowed DESC LIMIT 1;";
        $res=@mysqli_query($con,$getval);
        $oindex="CREATE Index borrowerIndex on BOOK_LOANS(CardNo)";
        $rind=@mysqli_query($con,$oindex);
        $cindex="Drop index borrowerIndex on BOOK_LOANS";

        $getval2="SELECT CardNo, TimesBorrowed FROM dcount
WHERE Year(DateOut) = ".$_GET['iyr']." AND Month(DateOut) = ".$_GET['im']."
ORDER BY TimesBorrowed DESC LIMIT 1;";

        $otable="CREATE Temporary Table tempT as SELECT COUNT(BookID) as TimesBorrowed, CardNo FROM BOOK_LOANS
        WHERE Year(DateOut) = ".$_GET['iyr']." AND Month(DateOut) = ".$_GET['im']." GROUP BY CardNo;";
        $ctable="DROP Table tempT;";
        $getval3="SELECT CardNo, TimesBorrowed FROM tempT ORDER BY TimesBorrowed DESC LIMIT 1;";
        $res3=@mysqli_query($con,$getval2);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>Original</th></tr>';
        if($res){
            echo '<tr><th>Card No</th><th>Times Borrowed</th></tr>';
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
            }
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo '<tr><th>Card No</th><th>Times Borrowed</th></tr>';
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                }
            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ View</th></tr>';



            if($res3){
                echo '<tr><th>Card No</th><th>Times Borrowed</th></tr>';
                while($row=@mysqli_fetch_row($res3)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                }
            }else{
                echo "Something went wrong with querry".'<br>';
            }
        echo '<tr><th>W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo '<tr><th>Card No</th><th>Times Borrowed</th></tr>';
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                }
            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==3){
        $getval = "select b.Title from BOOK as b, BOOK_AUTHORS as a
            where b.BookID = a.BookID and a.AuthorLastName = '".$_GET['lname']."' and a.AuthorFirstName = '".$_GET['fname']."'
            Group by b.Title;";
        $res=@mysqli_query($con,$getval);
        $jval="select b.Title from BOOK as b INNER JOIN BOOK_AUTHORS as a
            on b.BookID = a.BookID where b.BookID = a.BookID and a.AuthorLastName = '".$_GET['lname']."' 
            and a.AuthorFirstName = '".$_GET['fname']."' Group by b.Title";
        $oindex="CREATE INDEX temp on BOOK_AUTHORS (AuthorLastName, AuthorFirstName);";
        $rind=@mysqli_query($con,$oindex);
        $cindex="drop index temp on BOOK_AUTHORS;";

        $getval2="SELECT Title FROM library.abook where LName = '".$_GET['lname']."' and FName = '".$_GET['fname']."';";

        $otable="create temporary table TB as
                select b.Title
                from BOOK as b, BOOK_AUTHORS as a
                where b.BookID = a.BookID and a.AuthorLastName = '".$_GET['lname']."' and a.AuthorFirstName = '".$_GET['fname']."'
                Group by b.Title";
        $ctable="Drop table TB;";
        $getval3="select * from TB";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>Original</th></tr>';
        if($res){
            echo '<tr><th>Title</th></tr>';
            while($row=@mysqli_fetch_row($res)){
                echo '<tr><td>'.$row[0].'</td></tr>';
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>Inner Join</th></tr>';
        if($jres){
            echo '<tr><th>Title</th></tr>';
            while($row=@mysqli_fetch_row($jres)){
                echo '<tr><td>'.$row[0].'</td></tr>';
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo '<tr><th>Title</th></tr>';
                while($row=@mysqli_fetch_row($res2)){
                    echo '<tr><td>'.$row[0].'</td></tr>';
                }

            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th>W/ View</th></tr>';
        if($res3){
            echo '<tr><th>Title</th></tr>';
            while($row=@mysqli_fetch_row($res3)){
                echo '<tr><td>'.$row[0].'</td></tr>';
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr>W/ Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo '<tr><th>Title</th></tr>';
                while($row=@mysqli_fetch_row($res4)){
                    echo '<tr><td>'.$row[0].'</td></tr>';
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }

    }elseif($num==4){
        $getval = "SELECT BorrowerLName, BorrowerFName, COUNT(*)
                    FROM BOOK_LOANS bl, BORROWER b
                    WHERE bl.CardNo = b.CardNo and bl.BranchID = 01 and YEAR(bl.DueDate) = 2005 and MONTH(bl.DueDate) = 06 and bl.DueDate < bl.DateReturned
                    GROUP BY bl.CardNo";
        $res=@mysqli_query($con,$getval);
        $jval="SELECT BorrowerLName, BorrowerFName, COUNT(*) FROM Book_Loans bl INNER JOIN Borrower b on bl.CardNo = b.CardNo
                WHERE BranchID = ".$_GET['bid']." and YEAR(DueDate) = ".$_GET['iyr']." and MONTH(DueDate) =".$_GET['im']." and DueDate < DateReturned
                GROUP BY bl.CardNo";
        $oindex="Create Index cardIndex on Book_Loans(CardNo, BranchID)";
        $rind=@mysqli_query($con,$oindex);
        $cindex="Drop Index cardIndex on Book_Loans;";

        $getval2="SELECT BorrowerLName, BorrowerFName,çnt
                from lateret Where BranchID=".$_GET['bid']." and YEAR(DueDate) = ".$_GET['iyr']." and MONTH(DueDate) = ".$_GET['im']."";

        $otable="Create Temporary Table TB as SELECT BorrowerLName, BorrowerFName, bl.CardNo
                FROM Book_Loans bl INNER JOIN Borrower b on bl.CardNo = b.CardNo
                WHERE BranchID =".$_GET['bid']." and YEAR(DueDate) = ".$_GET['iyr']." and MONTH(DueDate) = ".$_GET['im']." and DueDate < DateReturned;";
        $ctable="Drop Table TB";
        $getval3="SELECT BorrowerLName, BorrowerFName, COUNT(*) FROM TB GROUP BY CardNo;";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>W/ Original</th></tr>';
        if($res){
            echo "<tr><th>Borrower Last Name</th><th>Borrower First Name</th><th>Count</th></tr>";
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';

        }
        echo '<tr><th>Inner Join</th></tr>';
        if($jres){
            echo "<tr><th>Borrower Last Name</th><th>Borrower First Name</th><th>Count</th></tr>";
            while($row=@mysqli_fetch_row($jres)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }

        }else{
            echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
        }
        echo '<tr><th>W/ Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo "<tr><th>Borrower Last Name</th><th>Borrower First Name</th><th>Count</th></tr>";
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
                }

            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th>W/ View</th></tr>';
        if($res3){
            echo "<tr><th>Borrower Last Name</th><th>Borrower First Name</th><th>Count</th></tr>";
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo "<tr><th>Borrower Last Name</th><th>Borrower First Name</th><th>Count</th></tr>";
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==5){
        $getval = "Select b.Title, bl.BranchName, bl.BranchAddress, COUNT(lb.BookID) AS TimesBorrowed
                From Book as b, Library_Branch as bl, Book_Loans as lb
                Where b.BookID = lb.BookID AND bl.BranchID = lb.BranchID AND MONTH(lb.DateOut) = ".$_GET['im']." AND YEAR(lb.DateOut) = ".$_GET['iyr']."
                Group by lb.BranchID
                ORDER BY COUNT(lb.bookID) DESC LIMIT 1";
        $res=@mysqli_query($con,$getval);
        $jval="SELECT Title, BranchName, lb.BranchAddress, COUNT(bl.BookID) AS TimesBorrowed
                FROM (book b INNER JOIN book_loans bl on b.bookID = bl.bookID) INNER JOIN library_branch lb on bl.branchID = lb.branchID
                WHERE MONTH(DateOut) = ".$_GET['im']." AND YEAR(DateOut) = ".$_GET['iyr']."
                GROUP by lb.BranchID
                ORDER BY COUNT(bl.bookID) DESC LIMIT 1";
        $oindex="Create Index temp on book_loans(DateOut, branchID, bookID)";
        $rind=@mysqli_query($con,$oindex);
        $cindex="drop index temp on book_loans";

        $getval2="SELECT Title, BranchName, BranchAddress, COUNT(BookID) as TimesBorrowed FROM mxbr
                WHERE MONTH(DateOut) = ".$_GET['im']." AND YEAR(DateOut) = ".$_GET['iyr']."
                GROUP BY BranchID
                ORDER BY COUNT(BookID) DESC LIMIT 1;";

        $otable="Create Temporary Table TB as SELECT Title, BranchName, lb.BranchAddress, bl.BookID,
                lb.BranchID, bl.DateOut FROM (book b INNER JOIN book_loans bl on b.bookID = bl.bookID) 
                INNER JOIN library_branch lb on bl.branchID = lb.branchID";
        $ctable="DROP Table TB";
        $getval3="SELECT Title, BranchName, BranchAddress, COUNT(BookID) as TimesBorrowed FROM TB
                WHERE MONTH(DateOut) = ".$_GET['im']." AND YEAR(DateOut) = ".$_GET['iyr']."
                GROUP BY BranchID
                ORDER BY COUNT(BookID) DESC LIMIT 1;";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>Original</th></tr>';
        if($res){
            echo "<tr><th>Title</th><th>Branch Name</th><th>Address</th><th>Times Borrowed</th></tr>";
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>Inner Join</th></tr>';
        if($jres){
            echo "<tr><th>Title</th><th>Branch Name</th><th>Address</th><th>Times Borrowed</th></tr>";
            while($row=@mysqli_fetch_row($jres)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo "<tr><th>Title</th><th>Branch Name</th><th>Address</th><th>Times Borrowed</th></tr>";
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }

            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th>W/ View</th></tr>';
        if($res3){
            echo "<tr><th>Title</th><th>Branch Name</th><th>Address</th><th>Times Borrowed</th></tr>";
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo "<tr><th>Title</th><th>Branch Name</th><th>Address</th><th>Times Borrowed</th></tr>";
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==6){
        $getval = "select maxbr.Title as 'Title',p.PublisherName as 'Publisher Name',
                p.Address as 'Address', p.Phone as 'Phone Number'
                from publisher p ,
                (select b.Title as 'Title',b.BookID as 'BookID',b.PublisherName as 'PublisherName', max(cnt) 'mxcnt'
                    from(select BookID,count(*) 'cnt'
                        from book_loans
                        group by BookID) as bcount, book b
                    where bcount.BookID=b.BookID
                    group by PublisherName) as maxbr
                where p.PublisherName=maxbr.PublisherName
                and p.PublisherName='".$_GET['publ']."'";



        $res=@mysqli_query($con,$getval);
        $jval="select maxbr.Title as 'Title',p.PublisherName as 'Publisher Name',
                p.Address as 'Address', p.Phone as 'Phone Number'
                from publisher p inner join 
                (select b.Title as 'Title',b.BookID as 'BookID',b.PublisherName as 'PublisherName', max(cnt) 'mxcnt'
                    from(select BookID,count(*) 'cnt'
                        from book_loans
                        group by BookID) as bcount inner join book b
                on bcount.BookID=b.BookID
                    group by PublisherName) as maxbr
                on p.PublisherName=maxbr.PublisherName
                where p.PublisherName='".$_GET['publ']."';";

        $oindex1="create index pub
                on publisher(PublisherName, Address, Phone);";
        $oindex2="create index books
                on Book(BookID, PublisherName)";
        $oindex3="create index loans
                on book_loans(BookID, BranchID, CardNo, Dateout, DueDate, DateReturned);";
        $rind1=@mysqli_query($con,$oindex1);
        $rind2=@mysqli_query($con,$oindex2);
        $rind3=@mysqli_query($con,$oindex3);
        $cindex1="Drop index pub on publisher;";
        $cindex2="Drop index books on Book;";
        $cindex3="Drop index loans on book_loans;";

        $getval2="Select Title, PublisherName as 'Publisher Name', Address, Phone as 'Phone Number'  from maxpub
                where PublisherName='".$_GET['publ']."';";

        $otable="create temporary table pubmaxtemp 
                as (select maxbr.Title as 'Title',p.PublisherName,
                p.Address as 'Address', p.Phone
                from publisher p inner join 
                (select b.Title as 'Title',b.BookID as 'BookID',b.PublisherName as 'PublisherName', max(cnt) 'mxcnt'
                    from(select BookID,count(*) 'cnt'
                        from book_loans
                        group by BookID) as bcount inner join book b
                on bcount.BookID=b.BookID
                    group by PublisherName) as maxbr
                on p.PublisherName=maxbr.PublisherName)";

        $ctable="Drop table pubmaxtemp";

        $getval3="Select Title, PublisherName as 'Publisher Name', Address, Phone as 'Phone Number' from pubmaxtemp
                where PublisherName='".$_GET['publ']."'";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th>Original</th></tr>';
        if($res){
            echo "<tr><th>Title</th><th>Publisher</th><th>Address</th><th>Phone</th></tr>";
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>Inner Join</th></tr>';
        if($jres){
            echo "<tr><th>Title</th><th>Publisher</th><th>Address</th><th>Phone</th></tr>";
            while($row=@mysqli_fetch_row($jres)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Index</th></tr>';
        if($rind1&&$rind2&&$rind3){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo "<tr><th>Title</th><th>Publisher</th><th>Address</th><th>Phone</th></tr>";
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }

            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex1);
            @mysqli_query($con,$cindex2);
            @mysqli_query($con,$cindex3);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th>W/ View</th></tr>';
        if($res3){
            echo "<tr><th>Title</th><th>Publisher</th><th>Address</th><th>Phone</th></tr>";
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th>W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo "<tr><th>Title</th><th>Publisher</th><th>Address</th><th>Phone</th></tr>";
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==7){
        $getval = "select b.Title, r.BranchName, l.DateOut, l.DueDate, l.DateReturned
                from library.book as b, library.library_branch as r, library.book_loans as l, library.borrower as o
                where o.CardNo = l.CardNo and l.BranchID = r.BranchID and l.BookID = b.BookID and o.BorrowerFName = '".$_GET['fname']."' 
                and o.BorrowerLName = '".$_GET['lname']."'
                order by l.DateOut;";
        $res=@mysqli_query($con,$getval);
        $jval="select b.Title, r.BranchName, l.DateOut, l.DueDate, l.DateReturned
            from library.book_loans as l INNER JOIN library.book as b INNER JOIN library.library_branch as r INNER JOIN library.borrower as o
            where o.CardNo = l.CardNo and l.BranchID = r.BranchID and l.BookID = b.BookID and o.BorrowerFName = '".$_GET['fname']."' and o.BorrowerLName = '".$_GET['lname']."'
            order by l.DateOut;";

        $oindex="CREATE INDEX temp on book_loans(CardNo);";
        $rind=@mysqli_query($con,$oindex);
        $cindex="drop index temp on book_loans;";


        $getval2="SELECT * FROM library.bdata where FName = '".$_GET['fname']."' and LName = '".$_GET['lname']."'";

        $otable="create temporary table LOANdata as
                select b.Title, r.BranchName, l.DateOut, l.DueDate, l.DateReturned
                from library.book as b, library.library_branch as r, library.book_loans as l, library.borrower as o
                where o.CardNo = l.CardNo and l.BranchID = r.BranchID and l.BookID = b.BookID and o.BorrowerFName = '".$_GET['fname']."' and o.BorrowerLName = '".$_GET['lname']."'
                order by l.DateOut";
        $ctable="Drop table LOANdata";
        $getval3="Select * from LOANdata;;";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th> Original</th></tr>';
        if($res){
            echo "<tr><th>Title</th><th>Branch</th><th>DateOut</th><th>DueDate</th><th>DateReturned</th></tr>";
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> Inner Join</th></tr>';
        if($jres){
            echo "<tr><th>Title</th><th>Branch</th><th>DateOut</th><th>DueDate</th><th>DateReturned</th></tr>";
            while($row=@mysqli_fetch_row($jres)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> W/ Index</th></tr>';
        if($rind){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo "<tr><th>Title</th><th>Branch</th><th>DateOut</th><th>DueDate</th><th>DateReturned</th></tr>";
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
                }

            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th> W/ View</th></tr>';
        if($res3){
            echo "<tr><th>Title</th><th>Branch</th><th>DateOut</th><th>DueDate</th><th>DateReturned</th></tr>";
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> W/ Temp Table</th></tr>';
        if($tm){

            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo "<tr><th>Title</th><th>Branch</th><th>DateOut</th><th>DueDate</th><th>DateReturned</th></tr>";
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }elseif($num==8){
        $getval = "select b.Title as 'Title',concat(ba.AuthorFirstName,' ',ba.AuthorLastName) as 'Author'
                    ,concat(br.BorrowerFName,' ',br.BorrowerLName) as 'Borrower'
                    from book_authors ba, book b,
                    (select bl.BookID as'BookID', bl.CardNo as 'CardNo'
                    from (select CardNo, max(cnt) 'mxcnt'
                        from(select BookID,CardNo,count(*) 'cnt'
                            from book_loans
                            group by BookID,CardNo) as bcount	
                        group by CardNo) as mcnt inner join
                    (select *,count(*) as 'cnt'  
                    from book_loans group by BookID,CardNo) as bl
                    on bl.CardNo=mcnt.CardNo 
                    and bl.cnt=mcnt.mxcnt) as bl,
                    borrower br
                    where ba.BookID=b.BookID 
                    and  b.BookID=bl.BookID 
                    and bl.CardNo=br.CardNo
                    and br.CardNo= ".$_GET['cn'].";";

        $res=@mysqli_query($con,$getval);

        $jval="select b.Title as 'Title',concat(ba.AuthorFirstName,' ',ba.AuthorLastName) as 'Author'
                ,concat(br.BorrowerFName,' ',br.BorrowerLName) as 'Borrower'
                from book_authors ba inner join book b
                on ba.BookID=b.BookID inner join
                (select bl.BookID as'BookID', bl.CardNo as 'CardNo'
                from (select CardNo, max(cnt) 'mxcnt'
                    from(select BookID,CardNo,count(*) 'cnt'
                        from book_loans
                        group by BookID,CardNo) as bcount	
                    group by CardNo) as mcnt inner join
                (select *,count(*) as 'cnt'  
                from book_loans group by BookID,CardNo) as bl
                on bl.CardNo=mcnt.CardNo 
                and bl.cnt=mcnt.mxcnt) as bl 
                on b.BookID=bl.BookID inner join 
                borrower br on bl.CardNo=br.CardNo
                where br.CardNo=".$_GET['cn']."";

        $oindex1="create index bor on borrower(CardNo,BorrowerLName,BorrowerFName,Address,Phone)";

        $oindex2="create index books on Book(BookID, PublisherName)";
        $oindex3="create index loans on book_loans(BookID, BranchID, CardNo, Dateout, DueDate, DateReturned)";
        $oindex4="create index author on book_authors(BookID,AuthorLastName,AuthorFirstName)";
        $rind1=@mysqli_query($con,$oindex1);
        $rind2=@mysqli_query($con,$oindex2);
        $rind3=@mysqli_query($con,$oindex3);
        $rind4=@mysqli_query($con,$oindex4);
        $cindex1="Drop index bor on borrower;";
        $cindex2="Drop index books on Book;";
        $cindex3="Drop index loans on book_loans;";
        $cindex4="Drop index author on book_authors;";
        $getval2="SELECT Title, Author,Borrower FROM library.borrower_favorite where CardNo=".$_GET['cn'].";";

        $otable="create temporary table bor_fav as(
                select b.Title as 'Title',concat(ba.AuthorFirstName,' ',ba.AuthorLastName) as 'Author'
                ,concat(br.BorrowerFName,' ',br.BorrowerLName) as 'Borrower', br.CardNo 'CardNo'
                from book_authors ba inner join book b
                on ba.BookID=b.BookID inner join
                (select bl.BookID as'BookID', bl.CardNo as 'CardNo'
                from (select CardNo, max(cnt) 'mxcnt'
                    from(select BookID,CardNo,count(*) 'cnt'
                        from book_loans
                        group by BookID,CardNo) as bcount	
                    group by CardNo) as mcnt inner join
                (select *,count(*) as 'cnt'  
                from book_loans group by BookID,CardNo) as bl
                on bl.CardNo=mcnt.CardNo 
                and bl.cnt=mcnt.mxcnt) as bl 
                on b.BookID=bl.BookID inner join 
                borrower br on bl.CardNo=br.CardNo);";

        $ctable="Drop table bor_fav;";

        $getval3="SELECT Title, Author,Borrower FROM bor_fav where CardNo=".$_GET['cn'].";";
        $res3=@mysqli_query($con,$getval2);
        $jres=@mysqli_query($con,$jval);
        $tm=@mysqli_query($con,$otable);
        echo '<tr><th> Original</th></tr>';
        if($res){
            echo "<tr><th>Title</th><th>Author</th><th>Borrower</th></tr>";
            while($row=@mysqli_fetch_row($res)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }
            echo " ".'<br>';
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> Inner Join</th></tr>';
        if($jres){
            echo "<tr><th>Title</th><th>Author</th><th>Borrower</th></tr>";
            while($row=@mysqli_fetch_row($jres)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }
            echo " ".'<br>';
        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> W/ Index</th></tr>';
        if($rind1&&$rind2&&$rind3&&$rind4){
            $res2=@mysqli_query($con,$getval);
            if($res){
                echo "<tr><th>Title</th><th>Author</th><th>Borrower</th></tr>";
                while($row=@mysqli_fetch_row($res2)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
                }
                echo " ".'<br>';
            }else {
                echo "Something went wrong with querry" . '<br>';
            }
            @mysqli_query($con,$cindex1);
            @mysqli_query($con,$cindex2);
            @mysqli_query($con,$cindex3);
            @mysqli_query($con,$cindex4);
        }else{
            echo "Something went wrong with querry".'<br>';
        }


        echo '<tr><th> W/ View</th></tr>';
        if($res3){
            echo "<tr><th>Title</th><th>Author</th><th>Borrower</th></tr>";
            while($row=@mysqli_fetch_row($res3)){
                echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
            }

        }else{
            echo "Something went wrong with querry".'<br>';
        }
        echo '<tr><th> W/ Temp Table</th></tr>';
        if($tm){
            $res4=@mysqli_query($con,$getval3);
            if($res4){
                echo "<tr><th>Title</th><th>Author</th><th>Borrower</th></tr>";
                while($row=@mysqli_fetch_row($res4)){
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>";
                }

            }else{
                echo "Something went wrong with querry".'<br>';
            }
            @mysqli_query($con,$ctable);
        }else{
            echo "Something went wrong with querry".'<br>';
        }
    }
    echo "</table></body></html>";
}else{
    echo "Something went wrong with connection";
}


?>

