function setTodaysDateInCalendar(calendarName)
{
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	document.getElementById(calendarName).value = today.getFullYear() + "-" + String(mm) + "-" + String(dd);
}

function setExpenseTodaysDateInCalendar()
{
    setTodaysDateInCalendar('expenseDate');
}

function setIncomeTodaysDateInCalendar()
{
    setTodaysDateInCalendar('incomeDate');
}

function setBalanceStartingDate()
{
    setTodaysDateInCalendar('balanceStartingDate');
}

function setBalanceEndingDate()
{
    setTodaysDateInCalendar('balanceEndingDate');
}