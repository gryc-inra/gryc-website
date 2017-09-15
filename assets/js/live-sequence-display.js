$(document).ready(function(){
    $('div.locus-feature').each(function(index) {
        var locus = $( this ).data("locus");
        var feature = $( this ).data("feature");
        var sequenceContainer = $( this ).find('div.fasta');
        var form = $( this ).find('form');

        form.removeClass('hidden');

        form.submit(function(event) {
            event.preventDefault();
            var upstream = $( this ).parent().find("input[name='upstream']").val();
            var downstream = $( this ).parent().find("input[name='downstream']").val();
            var showUtr = $( this ).parent().find("input[name='showUtr']").is(":checked");
            var showIntron = $( this ).parent().find("input[name='showIntron']").is(":checked");

            $.ajax({
                type: 'GET',
                url: Routing.generate('feature_sequence', { locus_name: locus, feature_name: feature, upstream: upstream, downstream: downstream, showUtr: showUtr, showIntron: showIntron }),
                dataType: 'html',
                success: function (html) {
                    sequenceContainer.first().html(html);
                }
            });
        });
    });
});
