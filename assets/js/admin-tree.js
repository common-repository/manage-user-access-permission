
function muap_admin_tree()
{

                // prettier-ignore
                let data = JSON.parse(document.getElementById("muap-tree-data").value);
                //console.log(JSON.parse(data));
                let tree = new Tree('.muap-tree', {
                    data: data,
                    closeDepth: 3,
                    loaded: function() {
                        this.values = JSON.parse(document.getElementById("muap-tree-value").value);
                    },
                    onChange: function() {
                        document.getElementById("muap-tree").value = this.values
                        //console.log(this.values);
                    }
                })
                const collection = document.getElementsByClassName("treejs-node");
                var i = 0;
                for (i = 0; i < collection.length; i++) {
                    collection[i].classList.add("treejs-node__close");
                }
}

var is_exists_tree = document.getElementById("muap-tree-data");
if(is_exists_tree)
{
    muap_admin_tree();
}