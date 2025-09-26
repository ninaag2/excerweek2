<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculator</title>
    <link rel="stylesheet" href="templates/style.css">
</head>
<body>
    <form action="" method="post">
        Value 1: <input type="text" name="val1" size=10>
        Value 2: <input type="text" name="val2" size=10>
        <hr />
        Calculation:<br />
        <input type="radio" name="calc" value="add" checked>Add
        <input type="radio" name="calc" value="sub">Subtract
        <input type="radio" name="calc" value="mul">Multiply
        <input type="radio" name="calc" value="div">Divide
        <br />
        <input type="submit" value="Calculate">
        <input type="reset" value="Clear">
    </form>
</body>
</html>