export default function (value) {
    if(_.isString(value)){
        return value.replaceAll("\n", '<br>').replaceAll(";", '<br>');
    } else {
        return value;
    }
};
