<?php include_once 'header.php'?>
<form action="" method="post" class="calculate-form" style="margin: 5px">
    <input type="text" name="x"  placeholder="Первое число">
    <select class="operations" name="operation">
        <option value='+'> + </option>
        <option value='-'> - </option>
        <option value="*"> * </option>
        <option value="/"> / </option>
    </select>
    <input type="text" name="y" placeholder="Второе число">

    <input class="submit_form" type="submit" name="submit" value="Получить ответ">
</form>

<?php
//include_once 'header.php';
include_once 'function.php';
//renderCalculatorHtml();
echo renderCalculator('x', 'y', 'operation');