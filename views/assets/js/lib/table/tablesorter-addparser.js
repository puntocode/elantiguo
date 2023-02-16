/**
 * Created by Fede on 28/09/2017.
 */
$.tablesorter.addParser({
    // set a unique id
    id: 'thousands',
    is: function(s) {
        // return false so this parser is not auto detected
        return false;
    },
    format: function(s) {
        // format your data for normalization
        return s.replace('$','').replace(/,/g,'');
    },
    // set type, either numeric or text
    type: 'numeric'
});

