<?php


if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_medalcate
{
    public function plugin_medalcate()
    {
        global $_G;
    }
}

class plugin_medalcate_home extends plugin_medalcate
{
    function medal_nav_extra()
    {
        function str_not_empty($str)
        {
            return !empty($str);
        }

        if ($_GET['action'] == 'log') return '';

        $medals = C::t('forum_medal')->fetch_all_data(1);
        $medalcates = C::t('#medalcate#medalcate')->fetch_all();
        $jsvars = '';

        $medalids = array();
        foreach ($medals as $medal) {
            $medalids[] = $medal['medalid'];
        }
        $jsvars .= 'medalids=[' . implode(',', $medalids) . '];';

        $catestr = '';
        $idstr = '';

        foreach ($medalcates as $cate) {
            $idstr = implode(',', array_filter(explode('|', $cate['medalid']), 'str_not_empty'));
            $catestr .= '{cateid:' . $cate['cateid'] . ',';
            $catestr .= '"catename":"' . $cate['catename'] . '",';
            $catestr .= '"medalid":[' . $idstr . ']},';
        }
        $catestr = rtrim($catestr, ',');

        $jsvars .= 'medalcates=[' . $catestr . '];';
        // Show Selects
        $selectstr = '选择分组: ';
        $selectstr .= <<<EOF
        <select style="-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);box-shadow: inset 0 1px 1px rgba(0,0,0,.075);font-size: 15px;font-weight: 600;background: rgba(255,255,255,0.4);border:1px solid rgba(0,0,0,0.4);cursor:pointer;padding:5px;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;"onchange="change_medalcate(this.options[this.options.selectedIndex].value)">
EOF;
        $selectstr .= '<option value ="-1">全部</option>';
        foreach ($medalcates as $cate) {
            $selectstr .= '<option value="' . $cate['cateid'] . '">' . $cate['catename'] . '</option>';
        }
        $selectstr .= '</select>';

        $jsvars .= "selectstr='" . $selectstr . "';";
        // JS Functions
        $jsfunctions = <<<EOF
        DEBUG=0
        function l(str){
            if(DEBUG) console.log(str);
        }
        
        function hide_medal(medalid){
            medalBlock=document.getElementById("medal_"+medalid).parentElement;
            medalBlock.style.display='none';
        }
        
        function show_medal(medalid){
            medalBlock=document.getElementById("medal_"+medalid).parentElement;
            medalBlock.style.display='block';
        }
        
        function show_all_medals(medalids){
            l("Show Medals: "+medalids);
            medalids.forEach(medalid => {
                show_medal(medalid)
            });
        }
        
        function hide_all_medals(medalids){
            l("Hide Medals: "+medalids);
            medalids.forEach(medalid => {
                hide_medal(medalid)
            });
        }
        
        function change_medalcate(cateid){
            l("Change MedalCate: "+cateid);
            hide_all_medals(medalids);
            if(cateid==-1){show_all_medals(medalids)}
            else{
                medalcates.forEach(medalcate=>{
                    if(medalcate['cateid']==cateid){
                        l(medalcate['medalid']);
                        show_all_medals(medalcate['medalid']);
                    }
                });
            }
        }
        
        function insert_select(){
            ct=document.getElementById("ct");
            mn=ct.firstElementChild;
            bmbw0=mn.firstElementChild;
            ul1=bmbw0.lastElementChild;
            l(ul1);
            var div_select=document.createElement('div');
            div_select.style = 'font-size: 15px;font-weight: 600;';
            div_select.innerHTML=selectstr;
            bmbw0.insertBefore(div_select, ul1.previousSibling.previousSibling);
        }
EOF;

        $jscode = '<script>' . $jsfunctions . $jsvars;

        $jscode .= <<< EOF
        l("Debug Mode: "+DEBUG);
        insert_select();
        EOF;

        $jscode .= '</script>';

        return $jscode;
    }
}
