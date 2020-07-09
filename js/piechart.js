google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Expenses', 'Amount (in PLN)'],
	  ['Savings',  2000],
	  ['Children',  1500],
	  ['Rent', 1000],
	  ['Travels', 1000],
	  ['Pensions',  1000],
	  ['Food',  700],
	  ['Transport', 500],
	  ['Entertainments', 500],
	  ['Clothes',  300],
	  ['Hygiene',  200],
	  ['Books', 200],
	  ['Telecommunication', 50],
	  ['Others',  30],
	  ['Healthcare',  0],
	  ['Repayment of debts', 0],
	  ['Donations', 0]
	]);

  var options = {
	pieSliceText: 'label',
	title: 'Your expenses',
	pieStartAngle: 100,
	backgroundColor: 'smokewhite',
	titleTextStyle: {
        color: 'black'
    },
    hAxis: {
        textStyle: {
            color: 'black'
        },
        titleTextStyle: {
            color: 'black'
        }
    },
    vAxis: {
        textStyle: {
            color: 'black'
        },
        titleTextStyle: {
            color: 'black'
        }
    },
    legend: {
        textStyle: {
            color: 'black'
        }
    }
  };

	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
}
