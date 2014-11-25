
/////////////////////////////////////////////////////////////////////////////////
// MultiSelect
// 推荐使用MultiSelect.create()来生成对象，参数不变
// handle_array   [handle_select1, handle_select2, ...]
// opt_data_array [opt_data1, opt_data2, ... ]
// opt_data       {t:text, v:value, s:selected, opt_data_array:[opt_data_array] }
// custom_onchange_fun_array [customer_onchange_fun1, customer_onchange_fun2, ...] 参数可选
/////////////////////////////////////////////////////////////////////////////////
var MultiSelect=function(select_array, opt_data_array, ext_opt_data_array, custom_onchange_fun_array)
{
    if ( select_array instanceof Array && select_array.length > 0 ) {

        this.select = select_array[0];
        this.left_selects = [];
        for (var i=1; i<select_array.length; ++i) {
            this.left_selects.push(select_array[i]);
        }

        this.opt_data_array = opt_data_array || [];
        this.ext_opt_data_array = ext_opt_data_array || [];
        
        if ( !custom_onchange_fun_array ) {
            custom_onchange_fun_array = [];
            for ( var i=0;i<select_array.length;++i ) {
                custom_onchange_fun_array.push(select_array[i].onchange || function(){} );
            }
        }

        this.custom_onchange_fun = custom_onchange_fun_array[0];
        this.left_custom_funs = [];
        for (var i=1; i<custom_onchange_fun_array.length; ++i) {
            this.left_custom_funs.push(custom_onchange_fun_array[i]);
        }

        this.init();
    }
}

MultiSelect.create=function(select_array, opt_data_array, ext_data_array, custom_onchange_fun_array)
{
    var obj = new MultiSelect(select_array, opt_data_array, ext_data_array, custom_onchange_fun_array);
    MultiSelect["_OBJ_"+MultiSelect._OBJECT_NUM++] = obj;
    return obj;
}

MultiSelect._OBJECT_NUM = 0;

MultiSelect.prototype.init=function()
{
    this._initOption();

    if ( this.left_selects.length>0 ) {
        this._initOnchangeHandler();
    }

    if ( this.select.onchange ) {
        this.select.onchange(0,1);
    }
    return;
}

MultiSelect.prototype.getSelectByIndex=function(index)
{
    if (index == 0) {
        return this;   
    }
    if (this.left_selects.length==0) {
        return null
    }
    return this.next.getSelectByIndex(index-1);
}

MultiSelect.prototype.getSelectByHandle=function(select_handle)
{
    if (select_handle==this.select) {
        return this;
    }
    if (this.left_selects.length==0) {
        return null;
    }
    return this.next.getSelectByHandle(select_handle);
}

MultiSelect.prototype._initOption=function()
{
    this.select.length = 0;
    
    //var opt_fragment = document.createDocumentFragment();
    //this._createOptionDom(this.ext_opt_data_array, opt_fragment);
    //this._createOptionDom(this.opt_data_array, opt_fragment);
    //this.select.appendChild(opt_fragment);
    
    this._createOption(this.ext_opt_data_array);
    this._createOption(this.opt_data_array);
}  

MultiSelect.prototype._createOptionDom=function(opt_data_array, opt_fragment)
{
    for ( var i=0; i<opt_data_array.length; ++i ) {
 
        var opt_data = opt_data_array[i];
        var o = document.createElement("option");

        if ( opt_data.t==undefined || opt_data.t==null ) {
            opt_data.t="";
        }
        
        if ( opt_data.v==undefined || opt_data.v==null ) {
            opt_data.v=opt_data.t;
        }
        o.setAttribute("value", opt_data.v);

        if ( opt_data.s ) {
            o.setAttribute("selected", true);
        }

        var t = document.createTextNode(opt_data.t);
        o.appendChild(t);
        opt_fragment.appendChild(o);
    }
}

MultiSelect.prototype._createOption=function(opt_data_array)
{
    for ( var i=0; i<opt_data_array.length; ++i ) {
 
        var opt_data = opt_data_array[i];

        if ( opt_data.t==undefined || opt_data.t==null ) {
            opt_data.t="";
        }
        
        if ( opt_data.v==undefined || opt_data.v==null ) {
            opt_data.v=opt_data.t;
        }

        this.select.options[this.select.length] = new Option(opt_data.t, opt_data.v, false, (opt_data.s==true ) );
    }
}

MultiSelect.CALL_TYPE = {};
MultiSelect.CALL_TYPE.INIT = 0;     // 初始化调用
MultiSelect.CALL_TYPE.PROGRAM = 1;  // 页面中显式调用select.onchange()
MultiSelect.CALL_TYPE.BROWSER = 2;  // 用户触发的onchange事件时调用

MultiSelect.prototype._initOnchangeHandler=function()
{
    var this_multi_select = this;
    var select_handle = this_multi_select.select;
    var custom_onchange_fun = this_multi_select.custom_onchange_fun;

    select_handle.onchange = function(event,init) {
        
        event = window.event || event;
        var call_type = MultiSelect.CALL_TYPE.INIT;

        if ( !init ) {
            if ( !event ) {
                call_type = MultiSelect.CALL_TYPE.PROGRAM;
            }
            else {
                call_type = MultiSelect.CALL_TYPE.BROWSER;
            }
        }

        var args = {
            event: event,
            select: select_handle,            
            call_type: call_type,
            multi_select: this_multi_select
        };

        if ( custom_onchange_fun(args)==false ) {
            return;
        }

        this_multi_select.next = new MultiSelect(this_multi_select.left_selects, 
                                                              this_multi_select._getNextSelectOptArray(select_handle.value),
                                                              this_multi_select._getNextExtSelectOptArray(select_handle.value),
                                                              this_multi_select.left_custom_funs);
    }
}

MultiSelect.prototype._getNextSelectOptArray=function(value)
{
    for ( var i=0; i<this.opt_data_array.length; ++i ) {
        if ( this.opt_data_array[i].v == value ) {
            return this.opt_data_array[i].opt_data_array;
        }
    }
    return [];
}

MultiSelect.prototype._getNextExtSelectOptArray=function(value)
{
    for ( var i=0; i<this.ext_opt_data_array.length; ++i ) {
        if ( this.ext_opt_data_array[i].v == value ) {
            return this.ext_opt_data_array[i].opt_data_array;
        }
    }
    
    if ( this.ext_opt_data_array.length <= 0 ) {
        return [];
    }
    return this.ext_opt_data_array[0].opt_data_array || [];
}


/*  |xGv00|4667f8824809eac1d28f7ef7ab3e430d */