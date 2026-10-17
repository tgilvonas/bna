import {Chart} from "chart.js/auto";

document.addEventListener("DOMContentLoaded", function() {

    const formatData = function (dataToFormat, labelKey, dataKey) {
        let labels = [];
        let data = [];
        dataToFormat.forEach(function(dataItem){
            labels.push(dataItem[labelKey]);
            data.push(dataItem[dataKey]);
        });
        let formattedData = {
            labels: labels,
            data: data,
        }
        return formattedData;
    }

    const elementArticlesByCategory = document.getElementById('articles_by_category');
    const elementArticlesByYear = document.getElementById('articles_by_year');

    const formattedArticlesByCategory = formatData(articlesByCategory, 'categoryName', 'articles_published');
    const formattedArticlesByYear = formatData(articlesByYear, 'articles_year', 'articles_count');

    new Chart(elementArticlesByCategory, {
        type: 'pie',
        data: {
            labels: formattedArticlesByCategory.labels,
            datasets: [{
                data: formattedArticlesByCategory.data,
            }]
        }
    });
    new Chart(elementArticlesByYear, {
        type: 'bar',
        data: {
            labels: formattedArticlesByYear.labels,
            datasets: [{
                label: articlesByYearLabel,
                data: formattedArticlesByYear.data,
            }]
        }
    });
});
