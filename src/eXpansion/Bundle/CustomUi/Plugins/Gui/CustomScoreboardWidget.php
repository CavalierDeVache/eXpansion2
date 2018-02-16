<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetLabel;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class CustomScoreboardWidget extends WidgetFactory
{

    /**
     * ChatHelperWidget constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param                      $posX
     * @param                      $posY
     * @param WidgetFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $manialink->getFmlManialink()->setLayer("ScoresTable");

        $divider = $this->uiFactory->createLine(0, -3)->setLength(40)->setStroke(0.33)->setColor("fff");

        $frame = Frame::create()->setPosition(-50, 60);
        $frame->addChildren([
            $this->uiFactory->createLabel("Live Rankings", uiLabel::TYPE_TITLE)->setTextSize(3)
                ->setVerticalAlign("center2"),
            $divider,
        ]);
        $manialink->addChild($frame);

        $x = 0;
        $layout = $this->uiFactory->createLayoutLine(0, -6, [], 4);
        $layout->setId("PlayerFrame");
        for ($i = 0; $i < 2; $i++) {
            $column = $this->uiFactory->createLayoutRow(0, 0, [], -0.5);
            for ($j = 0; $j < 20; $j++) {
                $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
                $line->addClass("PlayerItem");

                $place = new WidgetLabel(($x + 1).".");
                $place->setAlign("center", "center2")->setSize(6, 3);

                $flag = Quad::create();
                $flag->setAlign("left", "center2")->setSize(3, 3);

                $nick = new WidgetLabel("");
                $nick->setTextPrefix(" ");
                $nick->setTextFont("BiryaniDemiBold");
                $nick->addDataAttribute("index", $x);
                $nick->addClass("doSpec");
                $nick->setAreaFocusColor("fffa");
                $nick->setAlign("left", "center2")->setSize(30, 3);

                $spec = new WidgetLabel("  ");
                $spec->setTextFont("")->setTextSize(1);
                $spec->addDataAttribute("index", $x);
                $spec->addClass("doSpec");
                $spec->setAreaFocusColor("fffa");
                $spec->setAlign("center", "center2")->setSize(3, 3);

                $profile = new WidgetLabel("   ");
                $profile->setTextFont("")->setTextSize(1);
                $profile->addDataAttribute("index", $x);
                $profile->addClass("doProfile");
                $profile->setAreaFocusColor("fffa");
                $profile->setAlign("center", "center2")->setSize(3, 3);

                $status = new WidgetLabel('');
                $status->setText(0.75);
                $status->setTextFont("BiryaniDemiBold");
                $status->setAlign("center", "center2")->setSize(6, 3);

                $score = new WidgetLabel("0:00.000");
                $score->setTextFont("BiryaniDemiBold");
                $score->setAlign("center", "center2")->setSize(12, 3);

                $line->addChildren([
                    $place,
                    $score,
                    $flag,
                    $nick,
                    $status,
                    $spec,
                    // $profile,


                ]);

                $column->addChild($line);
                $x++;
            }
            $layout->addChild($column);
        }
        $frame->addChild($layout);
        $divider->setLength($layout->getWidth());
        $frame->setX(-($layout->getWidth() / 2));
        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
 // TextExt.min.script.txt by prgfx
 declare Text _TextExt_lowercase;declare Text _TextExt_uppercase;declare Text[Integer]_TextExt_asciitable;declare Boolean _TextExt_initiated;Text TextExt_CharAt(Text string,Integer offset){if(offset<=TextLib::Length(string)){return TextLib::SubString(string,offset,1);}return"";}Void _TextExt_asciitable_init(){_TextExt_asciitable=[32=>" ",33=>"!",34=>"\"",35=>"#",36=>"\$",37=>"%",38=>"&",39=>"'",40=>"(",41=>")",42=>"*",43=>"+",44=>",",45=>"-",46=>".",47=>"/",48=>"0",49=>"1",50=>"2",51=>"3",52=>"4",53=>"5",54=>"6",55=>"7",56=>"8",57=>"9",58=>":",59=>";",60=>"<",61=>"=",62=>">",63=>"?",64=>"@",65=>"A",66=>"B",67=>"C",68=>"D",69=>"E",70=>"F",71=>"G",72=>"H",73=>"I",74=>"J",75=>"K",76=>"L",77=>"M",78=>"N",79=>"O",80=>"P",81=>"Q",82=>"R",83=>"S",84=>"T",85=>"U",86=>"V",87=>"W",88=>"X",89=>"Y",90=>"Z",91=>"[",92=>"\\\",93=>"]",94=>"^",95=>"_",96=>"`",97=>"a",98=>"b",99=>"c",100=>"d",101=>"e",102=>"f",103=>"g",104=>"h",105=>"i",106=>"j",107=>"k",108=>"l",109=>"m",110=>"n",111=>"o",112=>"p",113=>"q",114=>"r",115=>"s",116=>"t",117=>"u",118=>"v",119=>"w",120=>"x",121=>"y",122=>"z",123=>"{",124=>"|",125=>"}",126=>"~"];}Integer TextExt_CharCodeAt(Text string,Integer offset){_TextExt_asciitable_init();declare Text char=TextExt_CharAt(string,offset);if(_TextExt_asciitable.exists(char))return _TextExt_asciitable.keyof(char);return 0;}Text TextExt_FromCharCode(Integer code){_TextExt_asciitable_init();if(_TextExt_asciitable.existskey(code))return _TextExt_asciitable[code];return"";}Text[]TextExt_Chars(Text string,Boolean whitespaces){declare Text[]result;declare Text char;for(i,0,TextLib::Length(string)-1){char=TextExt_CharAt(string,i);if(char!=" "||whitespaces)result.add(char);}return result;}Text[]TextExt_Chars(Text string){return TextExt_Chars(string,False);}Text _TextExt_charCase(Text char,Text mode){if(!_TextExt_initiated){_TextExt_lowercase="abcdefghijklmnopqrstuvwxyzäöü";_TextExt_uppercase="ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÜ";_TextExt_initiated=True;}declare Text[]_lower=TextExt_Chars(_TextExt_lowercase);declare Text[]_upper=TextExt_Chars(_TextExt_uppercase);if(mode=="u"&&_lower.exists(char))return _upper[_lower.keyof(char)];if(mode=="l"&&_upper.exists(char))return _lower[_upper.keyof(char)];return char;}Text TextExt_TrimL(Text string){declare Integer i=0;while(TextExt_CharAt(string,i)==" "||TextExt_CharAt(string,i)=="\\n"||TextExt_CharAt(string,i)=="\\t"){i+=1;}return TextLib::SubString(string,i,TextLib::Length(string)-i);}Text TextExt_TrimR(Text string){declare Integer i=TextLib::Length(string)-1;while(TextExt_CharAt(string,i)==" "||TextExt_CharAt(string,i)=="\\n"||TextExt_CharAt(string,i)=="\\t"){i-=1;}return TextLib::SubString(string,0,i+1);}Text TextExt_Trim(Text string){declare Text result=string;result=TextExt_TrimL(result);result=TextExt_TrimR(result);return result;}Boolean TextExt_Empty(Text string){return TextExt_Trim(string)=="";}Text TextExt_Join(Text glue,Text[]strings){declare Text result="";for(i,0,strings.count-2){result^=strings[i]^glue;}result^=strings[strings.count-1];return result;}Text[]TextExt_Split(Text delimiter,Text string,Integer length){declare Text[]splitted=TextLib::Split(delimiter,string);declare Text[]result;if(length>=0){for(i,0,length-2){if(splitted.existskey(i)){result.add(splitted[i]);declare Temp=splitted.removekey(i);}}result.add(TextExt_Join(delimiter,splitted));}else{result=splitted;}return result;}Text[]TextExt_Split(Text delimiter,Text string){return TextExt_Split(delimiter,string,-1);}Text TextExt_Repeat(Text string,Integer count,Text separator){declare Text result="";for(i,0,count-1){result^=string;if(i<count-1){result^=separator;}}return result;}Text TextExt_Repeat(Text string,Integer count){return TextExt_Repeat(string,count,"");}Text[]TextExt_Words(Text string,Text separator){declare Text[]splitted=TextLib::Split(separator,string);declare Text[]result;foreach(word in splitted){if(!TextExt_Empty(word))result.add(word);}return result;}Text[]TextExt_Words(Text string){return TextExt_Words(string," ");}Text TextExt_Uppercase(Text string){declare Text result;for(i,0,TextLib::Length(string)-1){result^=_TextExt_charCase(TextExt_CharAt(string,i),"u");}return result;}Text TextExt_Lowercase(Text string){declare Text result;for(i,0,TextLib::Length(string)-1){result^=_TextExt_charCase(TextExt_CharAt(string,i),"l");}return result;}Text TextExt_Capitalize(Text string){declare Text[]result=TextExt_Chars(string,True);result[0]=_TextExt_charCase(result[0],"u");log(result);return TextExt_Join("",result);}Text TextExt_Titleize(Text string){declare Text[]splitted=TextLib::Split(" ",string);declare Text[]result;foreach(word in splitted){result.add(TextExt_Capitalize(word));}return TextExt_Join(" ",result);}Text TextExt_Reverse(Text string){declare Text result="";declare Integer i=TextLib::Length(string)-1;while(i>=0){result^=TextExt_CharAt(string,i);i-=1;}return result;}Integer TextExt_StrPos(Text heystack,Text needle){declare Integer d=TextLib::Length(needle);declare Integer n=TextLib::Length(heystack);if(d>n)return-1;for(i,0,n-d){if(TextLib::SubString(heystack,i,d)==needle)return i;}return-1;}Boolean TextExt_Contains(Text heystack,Text needle){return(TextExt_StrPos(heystack,needle)>=0);}Boolean TextExt_StartsWith(Text string,Text start){return TextLib::SubString(string,0,TextLib::Length(start))==start;}Boolean TextExt_EndsWith(Text string,Text end){return TextLib::SubString(string,TextLib::Length(string)-TextLib::Length(end),TextLib::Length(end))==end;}Text TextExt_Replace(Text search,Text replace,Text subject,Integer count){declare Integer toRun=count;declare Integer slen=TextLib::Length(search);declare Text result=subject;declare Integer start;while(toRun>=0||(count<0&&TextExt_StrPos(result,search)>0)){toRun-=1;start=TextExt_StrPos(result,search);result=TextLib::SubString(result,0,start)^replace^TextLib::SubString(result,start+slen,TextLib::Length(result));}return result;}Text TextExt_Replace(Text search,Text replace,Text subject){return TextExt_Replace(search,replace,subject,-1);}Text TextExt_Replace(Text[]search,Text[]replace,Text subject){declare Text result=subject;assert(search.count==replace.count);foreach(k=>v in search){result=TextExt_Replace(v,replace[k],result);}return result;}Text TextExt_Replace(Text[]search,Text replace,Text subject){declare Text[]repl;for(i,1,search.count){repl.add(replace);}return TextExt_Replace(search,repl,subject);}Real TextExt_Levenshtein(Text word1,Text word2){declare n=TextLib::Length(word1);declare m=TextLib::Length(word2);declare Real[Integer][Integer]matrix;for(i,0,n){matrix[i]=Real[Integer];matrix[i][0]=MathLib::ToReal(i);for(j,1,m){if(i==0)matrix[i][j]=MathLib::ToReal(j);else matrix[i][j]=0.;}}declare Real min;declare Integer isEqual;declare Int3 cost=<1,1,1>;for(i,1,n){for(j,1,m){if(TextExt_Lowercase(TextLib::SubString(word1,j-1,1))==TextExt_Lowercase(TextLib::SubString(word2,i-1,1)))isEqual=0;else isEqual=1;min=matrix[i-1][j]+cost[0];if(matrix[i][j-1]+cost[1]<min)min=matrix[i][j-1]+cost[1];if(matrix[i-1][j-1]+cost[2]<min)min=matrix[i-1][j-1]+cost[2];if(matrix[i-1][j-1]+isEqual<min)min=matrix[i-1][j-1]+isEqual;matrix[i][j]=min;}}return matrix[n][m];}Text[]TextExt_Levenshtein(Text input,Text[]p){declare Integer key1=0;declare Text[]possibilities=p;declare Text[]ordered;if(possibilities.count>1){declare Integer key2;declare Real[]levenshteinValues;foreach(word in possibilities){levenshteinValues.add(TextExt_Levenshtein(input,word));}declare Real min1;declare Real min2;while(levenshteinValues.count>0){min1=levenshteinValues[0];key1=0;min2=0.;key2=0;foreach(i=>v in levenshteinValues){if(v<=min1){key2=key1;key1=i;min1=v;}}ordered.add(possibilities[key1]);declare Temp=possibilities.removekey(key1);declare Temp2=levenshteinValues.removekey(key1);if(possibilities.count>0){ordered.add(possibilities[key2]);Temp=possibilities.removekey(key2);Temp2=levenshteinValues.removekey(key2);}} }return ordered;}Text TextExt_StripFormat(Text string,Boolean sc,Boolean sf,Boolean sl){declare Text result;declare Integer length=TextLib::Length(string);if(length<2)return string;declare Text[]ft=["w","n","o","i","s"];declare Text[]lt=["l","h","p"];declare Text[]ct=["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];declare Text char;declare Text ochar;declare Text state="0";declare Text color="";declare Text linktag="";for(i,0,length-1){ochar=TextExt_CharAt(string,i);char=TextExt_Lowercase(ochar);switch(state){case"0":{if(char=="\$")state="tag";else result^=ochar;}case"tag":{if(ft.exists(char)&&sf){state="0";}else if(ct.exists(char)&&sc){color=ochar;state="c1";}else if(lt.exists(char)&&sl){linktag=char;state="link";}else{result^="\$"^ochar;state="0";}}case"c1":{if(ct.exists(char)){color^=char;state="c2";}else{result^=ochar;state="0";}}case"c2":{if(ct.exists(char)){color^=char;}else{result^=ochar;}state="0";}case"link":{if(char=="["){state="linkurl";}else if(char==" "){result^=ochar;state="0";}else{result^=ochar;}}case"linkurl":{if(char=="]"){state="0";}} }}return result;}Text TextExt_StripFormat(Text string){return TextExt_StripFormat(string,False,True,False);}Text TextExt_StripColors(Text string){return TextExt_StripFormat(string,True,False,False);}Text TextExt_StripLinks(Text string){return TextExt_StripFormat(string,False,False,True);}Text TextExt_StripTags(Text string){return TextExt_StripFormat(string,True,True,True);}
            
            Text FormatSec(Real sec, Text color, Text highlite) {
                if (sec > 10.) {
                    return highlite ^ TextLib::FormatReal(sec,3,False,False);
                } 
                return color ^ 0 ^ highlite ^ TextLib::FormatReal(sec,3,False,False);                                
            }
            
            Text TimeToText(Integer intime) {
                declare Text highlite = "\$eff";
                declare Text color = "\$bcc";
                if (intime == -1) {
                    return color ^ "-:--.---";
                }
                
                declare time = MathLib::Abs(intime);                	
                declare Integer cent = time % 1000;	
                declare Integer sec2 = (time / 1000) % 60;
                declare Real sec = 1. * sec2 + cent * 0.001;
                declare Integer min = (time / 60000) % 60;                                                
                declare Integer hour = time / 3600000;
                declare Text sign = "";
                if (intime < 0)  {
                    sign = "-";
                }
                
                if (hour > 0) {
                    return highlite ^ sign ^ hour ^ "'" ^ TextLib::FormatInteger(min,2) ^ ":" ^ FormatSec(sec, highlite,highlite);
                }
                
                if (min == 0) {
                    return color ^ sign ^ "00:" ^ FormatSec(sec, color, highlite);
                }
                                                            
                if (min > 10)  {
                   return highlite ^ sign ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);
                } 
                
                return color ^ sign ^ 0 ^ highlite ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);                  
                                                                     
            }       
            
            Void updateScoreTable() {
                Page.GetClassChildren ("PlayerItem", Page.MainFrame, True);
                declare CTmMlPlayer[Text] PlayersByLogin = CTmMlPlayer[Text];
                foreach (Player in Players) {
                    PlayersByLogin[Player.User.Login] <=> Player;
                }
               
                foreach (key => Item in Page.GetClassChildren_Result) {
                    declare Frame <=> (Item as CMlFrame);
                    if (Scores.existskey(key)) {
                        Frame.Show();
                                                           
                        declare Text Fame = "";
                        declare Text Status = "";
                        declare Text Login = Scores[key].User.Login;
                        if (Scores[key].User.FameStars > 0) {
                            Status ^= " \$fff★ ";
                        } 
                        if (PlayersByLogin.existskey(Login)) {
                            if (PlayersByLogin[Login].RequestsSpectate) {
                                Status ^= "\$fff📷";
                            }
                        } else {
                             Status ^= "\$999✖";
                        }
                        if (Login == InputPlayer.User.Login) {
                         
                        } 
                       
                        (Frame.Controls[1] as CMlLabel).Value =   TimeToText(Scores[key].BestLap.Time);
                        (Frame.Controls[2] as CMlQuad).ImageUrl = Scores[key].User.CountryFlagUrl;
                        (Frame.Controls[3] as CMlLabel).Value =  TextExt_StripLinks(Scores[key].User.Name);
                        (Frame.Controls[4] as CMlLabel).Value = Status;
        
                    } else {
                        Frame.Hide();
                    }
                }
            } 

EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
          declare Text oldTime = "";      
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, <<<EOL
              if (CurrentLocalDateText != oldTime) {
                 updateScoreTable();
              }            
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::MouseClick, <<<EOL
              if (Event.Control.HasClass("doSpec")) {
                    declare index = TextLib::ToInteger(Event.Control.DataAttributeGet("index"));
                    if (Scores.existskey(index)) {
                        declare Text Login = Scores[index].User.Login;
                        RequestSpectatorClient(True);
                        SetSpectateTarget(Login);
                    }
              }   
              
              if (Event.Control.HasClass("doProfile")) {
                    declare index = TextLib::ToInteger(Event.Control.DataAttributeGet("index"));
                    if (Scores.existskey(index)) {
                        ShowProfile (Scores[index].User.Login);
                    }
              }            
                       
EOL
        );

    }


}
