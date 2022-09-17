export var makeStaticArray = function(num, val) {
    let arr = [];
    for(var i=0; i < num; i++) {
        arr.push(val)
    }
    return arr
}