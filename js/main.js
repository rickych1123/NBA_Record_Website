function showData(str) {
    if (str == "") {
        document.getElementById("mainTable").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("mainTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "/NBA_Record_Website/js/getDailyData.php?q=" + str, true);
        xmlhttp.send();
    }
}

function showSideBar(str) {
    if (str == "") {
        document.getElementById("sidebar").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("sidebar").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "/NBA_Record_Website/js/getSideBar.php?q=" + str, true);
        xmlhttp.send();
    }
}
$(document).ready(function() {
    $.ajax({
        url: "http://localhost/NBA_Record_Website/js/getTeamSprdData.php",
        method: "GET",
        success: function(data) {
            console.log(data);
            var team = [];
            var sprd = [];

            for (var i in data) {
                if (data[i].home_team_name == '邁阿密熱火') {
                    team.push(' vs ' + data[i].away_team_name + '(' + data[i].away_team_sprd + ')');
                    sprd.push(parseFloat(data[i].home_score) + parseFloat(data[i].home_team_sprd) - parseFloat(data[i].away_score));
                } else {
                    team.push(' @ ' + data[i].home_team_name + '(' + data[i].home_team_sprd + ')');
                    sprd.push(parseFloat(data[i].away_score) + parseFloat(data[i].away_team_sprd) - parseFloat(data[i].home_score));
                }
            }

            var chartdata = {
                labels: team,
                datasets: [{
                    label: '盤口差',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(160, 52, 64, 0.2)',
                        'rgba(99, 200, 34, 0.2)',
                        'rgba(120, 140, 40, 0.2)',
                        'rgba(190, 80, 150, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(160, 52, 64, 1)',
                        'rgba(99, 200, 34, 1)',
                        'rgba(120, 140, 40, 1)',
                        'rgba(190, 80, 150, 1)'
                    ],
                    hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: sprd
                }]
            };
            Chart.defaults.global.responsive = true;

            var ctx = $("#mycanvas");

            var barGraph = new Chart(ctx, {
                type: 'bar',
                data: chartdata,
                options: {
                    responsive: true
                }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
    $('body').scrollspy({
        target: '.bs-docs-sidebar',
        offset: 40
    });
});
