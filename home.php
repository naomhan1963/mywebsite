<!DOCTYPE html>
<html>
<head>
    <title>Teacher Schedule</title>
</head>
<body>
    <h1>Teacher Schedule</h1>
    
    <form method="post" action="viewClasses.php">
        <label for="teacherId">Teacher ID:</label>
        <input type="text" id="teacherId" name="teacherId" required>
        <br/>
        <br/>
        <label for="dayOfWeek">Day of the Week:</label>
        <select id="dayOfWeek" name="dayOfWeek" required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
        </select>
        <br/>
        <br/>
        <input type="submit" value="Get Schedule">
    </form>

</body>
</html>
