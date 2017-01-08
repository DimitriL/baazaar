$(document).foundation()

$(document).ready(function(){
    $('.search').change(function(){
        var keyword = $(this).val();
        console.log(keyword);
        $.get(Routing.generate('baazaar_baazaar_search', {keyword: keyword } , true), function( data ) {
            console.log(data);
        });
    });
});
