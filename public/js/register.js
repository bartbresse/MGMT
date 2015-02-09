/**
 * 
 * @author Bart Bresse
 * @description Mgmt notification system, takes care of AJAX data updates mostly
 */

functions = {};
keys = [];

function attach(name,o)
{
    math = Math.floor((Math.random() * 10) + 1);
    
    functions[name+math] = name;
    functions[name+math+'parameters'] = o;
    keys.push(name+math);
}

function detach()
{
    //functions
}

function clear()
{
    functions = {};
}

function notify()
{
    for(var i = 0;i < keys.length;i++)
    {
        functions[keys[i]];
        functions[keys[i]+'parameters'];
        
        var fn = window[functions[keys[i]]]; 
        if(typeof fn === 'function') {
            fn(functions[keys[i]+'parameters']);
        }
       // alert(i);
    }
}

