## test functionality of miner.py ##

from pyquery import PyQuery as pq
import miner as m

def test_unwiki():
    # test basic text
    assert (m.unwiki("text") == "text")

    # test italics, link removal
    assert (m.unwiki("''[[Galaxians]]''") == "Galaxians")

    # test link target removal
    assert (m.unwiki("''[[Meteoroids (video game)|Meteoroids]]''") == "Meteoroids")

    # test template removal
    assert (m.unwiki("{{Anchor|0–9}}''[[3 Ninjas Kick Back (video game)|3 Ninjas Kick Back]]''") == "3 Ninjas Kick Back")

    # test style removal
    assert (m.unwiki("width=16%|Developer<br><ref name=GS/><ref name=GC/>") == "Developer")

    # test tag removal
    assert (m.unwiki("<span style=\"display:none\">7</span>''[[The 7th Saga]]''") == "The 7th Saga")

    # test standalone tag removal
    assert (m.unwiki("Buster Bros. </center>") == "Buster Bros.")

    # test tag removal, w/ inner contents preserved
    assert (m.unwiki("{{vgy|1994}}<sup>NA</sup><br />{{vgy|1995}}<sup>EU, JP</sup><br />") == "1994 NA | 1995 EU, JP")

    # test tag removal, when no end tag present
    assert (m.unwiki("<span id=\"L\">''[[Lalaloopsy: Carnival of Friends]]''") == "Lalaloopsy: Carnival of Friends")

    # test link removal, with multiple links
    assert (m.unwiki("[[Ian Andrew]], [[Chris Andrew]]") == "Ian Andrew, Chris Andrew")

    # test link target removal, with multple links
    assert (m.unwiki("[[Ernieware]], [[David Whittaker (video game composer)|David Whittaker]]") == "Ernieware, David Whittaker")

    # test automatically renamed links, w/ parantheses
    assert (m.unwiki("[[kingdom (biology)|]]") == "kingdom")

    # test automatically renamed links, w/ commas
    assert (m.unwiki("[[Seattle, Washington|]]") == "Seattle")

    # test automatically renamed links, w/ hidden namespace
    assert (m.unwiki("[[Wikipedia:Manual of Style (headings)|]]") == "Manual of Style")

    # remove tags before attempting to remove style attributes
    assert (m.unwiki("<span style=\\\"display:none\\\">Addams F A</span>\'\'[[The Addams Family (video game)|The Addams Family]]\'\'") == "The Addams Family")

    # remove vgy template
    assert (m.unwiki("{{vgy|1994|1994}}") == "1994")

    # remove html comments
    assert (m.unwiki("Seiji Fujihara.{{Citation needed|date=July 2010}}<!--藤原誠司-->") == "Seiji Fujihara.")

    # remove nihongo template
    assert (m.unwiki(" {{Nihongo|'''''D.I.S Airport'''''|Ｄ・Ｉ・Ｓエアポート|D.I.S Eapōto}}") == "D.I.S Airport")

    # remove target links before nihongo template
    assert (m.unwiki("{{Nihongo|'''''[[Shoot 'em up#Golden age and refinement|Kagirinaki Tatakai]]'''''|限りなき戦い}}") == "Kagirinaki Tatakai")

    # remove date template
    assert (m.unwiki("{{dts|2002|03|26}}") == "2002|03|26")
    assert (m.unwiki("{{dts|2014-03-04}}") == "2014-03-04")
    assert (m.unwiki("{{dts|2010|08|}}") == "2010|08|")
    assert (m.unwiki("{{date|2011-02-18|mdy}}") == "2011-02-18")

    # remove time template
    assert (m.unwiki("<span style=\"display:None\">2010-03-04</span> {{#time:F j, Y|2010-3-4 }}") == "2010-3-4")
    assert (m.unwiki("{{#time:F j, Y|}}") == "")

    # remove color template
    assert (m.unwiki("{{color|silver|Unreleased}}") == "Unreleased")

    # remove flagicon template
    assert (m.unwiki("{{Flagicon|US}} {{flagicon|JPN}}") == "US JPN")

    # remove yes / no / console templates
    assert (m.unwiki("{{Yes}}") == "Yes" and m.unwiki("{{No}}") == "No" and m.unwiki("{{Partial|Console}}") == "Partial")
    assert (m.unwiki("{{no|No}}") == "no")

    # remove sort template
    assert (m.unwiki("{{sort|Aeon Flux|''[[Aeon Flux (video game)|Aeon Flux]]''}}") == "Aeon Flux")

    # remove ref template
    assert (m.unwiki("''[[AFL Live 2003]]''{{ref|AUS|[AUS]}}") == "AFL Live 2003")

    # remove check mark, cross template
    assert (m.unwiki("{{check mark|15}}") == "check mark")
    assert (m.unwiki("{{cross|15}}") == "cross")

    # remove vgrelease template, with start date, with multiple companies
    assert (m.unwiki("{{Vgrelease|NA|{{Start date|1983}}}}") == "NA 1983")
    assert (m.unwiki("{{vgrelease|JP=Namco|NA=[[Atari, Inc.]]}}") == "JP Namco|NA Atari, Inc.")
    assert (m.unwiki("{{vgrelease|JP= Pack-In-Video}}{{vgrelease|NA= [[THQ]]}}") == "JP Pack-In-Video NA THQ")

    # remove time template
    assert (m.unwiki("{{#time:F j, Y|2006-05-19}}") == "2006-05-19")

    # remove disambiguation needed template
    assert (m.unwiki("Running Man, The{{dn|date=June 2014}}") == "The Running Man")
    assert (m.unwiki("Plague{{disambiguation needed|date=March 2014}}") == "Plague")

    # remove citation needed template
    assert (m.unwiki("Forgotten Memories: Alternate Realities{{citation needed|date=March 2014}}") == "Forgotten Memories: Alternate Realities")

    # remove link with single bracket
    assert (m.unwiki("''[http://www.brutaldeluxe.fr/unreleased/i942.html 1942]") == "1942")
    assert (m.unwiki("[http://www.oxeyegames.com/ Oxeye Game Studio]") == "Oxeye Game Studio")

    # test complex phrases
    assert (m.unwiki("""[[Square (company)|Square Product {{nowrap|Development Division 3}}]]<ref name="production teams">{{cite web |
                     author=Winkler, Chris |year=2003 |title=Square Enix Talks Current Status |url=http://www.rpgfan.com/news/2003/1934.html |work=RPGFan |
                     accessdate=August 1, 2007}}</ref><br />[[Square Enix#Production teams|Square Enix Product {{nowrap|Development Division 3}}]]<ref
                     name="production teams" />""") == "Square Product Development Division 3 | Square Enix Product Development Division 3")

    # sort template removal should be non-greedy
    assert (m.unwiki("{{sort|King of Fighters 2002|''[[The King of Fighters 2002]]''}}{{ref|JP|[JP]}}") == "The King of Fighters 2002")

    # template brackets are automatically balanced, so partial templates should be removed
    assert (m.unwiki("Rayman Legends{{cite web|author=La rédac|title=") == "Rayman Legends")

    # test html comment removal, for single line and multiline comments
    assert (m.unwiki("<!-- Insufficient proof. Only front cover scans found. -->") == "")
    assert (m.unwiki("<!-- See: http://www.smspower.org/db/car_licence-gg-jp.shtml" + 
                     "-- 1) Internal to Mitsubishi Corporation.  Not released outside of the company." +
                     "-- 2) Not a game.") == "")

    # test preserving content inside strikeout tag
    assert (m.unwiki("""<s>''[[Donkey Kong (video game)|Donkey Kong: Original Edition]]''</s> '''(Limited release)''' <ref name="limited release" />""") \
            == "Donkey Kong: Original Edition (Limited release)")

    # make sure html comments and tags are removed properly
    assert (m.unwiki("''[[The Secret Saturdays: Beasts of the 5th Sun]]''<ref>[http://www.gamespot.com/psp/action/thesecretsaturdaysbeastsofthe5thsun/" + 
                     "similar.html?mode=versions The Secret Saturdays: Beasts of the 5th Sun for PSP - The Secret Saturdays: Beasts of the 5th Sun PSP Game - " +
                     "The Secret Saturdays: Beasts of the 5th Sun PSP Video Game<!-- Bot generated title -->]</ref>") == "The Secret Saturdays: Beasts of the 5th Sun")

    # make sure blank lines are not included when combining multiple lines
    assert (m.unwiki("''[[F1 (video game)|F1]]''<br><center>''Formula One''<small><sup>BR</sup></small></center> ") == "F1")

def test_year():
    assert (m.year("1/10/1990") == "1990")
    assert (m.year("July 4, 2012") == "2012")
    assert (m.year("2005-11-23") == "2005")
    assert (m.year("2002 03 26") == "2002")
    assert (m.year("2014") == "2014")
    assert (m.year("1995 EU, JP") == "1995 EU, JP")
    assert (m.year("1994\n\n1995\n") == "1994 | 1995")

    # make sure that 'year' only uses bar separators if multiple years found
    assert (m.year("partial | 2014|2|27") == "2014")    

    # test complex year combinations
    assert (m.year(m.unwiki("|1996-11-26<sup>NA</sup><br>1997-05<sup>UK</sup><br>2001-11-30<sup>UK</sup> (Midway Classics Re-Release)")) ==
            "1996 NA | 1997 UK | 2001 UK")

def test_reorder():
    assert (m.reorder("Demolition Man") == "Demolition Man")
    assert (m.reorder("Daedalus Encounter, The") == "The Daedalus Encounter")
    assert (m.reorder("Final Quest, The (1991)") == "The Final Quest (1991)")

def test_all():
    test_unwiki()
    test_year()
    test_reorder()

if __name__ == "__main__":
    test_all()
    print("Tests passed!")
