var u = {};


u.tree = function(list, parent,parentname,idname,le){
	if(le == 1){
        u.tree_data = new Array();
    }
    //保证找到的元素都放在一个数组$tree内,应该是$tree在所有的tree函数都共享    

    //遍历该数组，找到parent值为当前传递进来的$parent_id;
    for(var i in list) {
        var row = list[i];
        //判断
        if(row[parentname] == parent) {
            //保存起来
            row['le']=le;
            u.tree_data.push(row);
            //依据当前所找到的分类，找到其子节点，操作相同，递归完成
            this.tree(list, row[idname],parentname,idname,le+1);            
        }
    }
    return u.tree_data;
};

window.$tool = u;