## mine information from wiki pages ##

# import

from pyquery import PyQuery as pq
import urllib.request as urllib2
import re

# constants

wiki_base = "http://en.wikipedia.org"
wiki = wiki_base + "/wiki/Category:Video_game_lists_by_platform"
wiki2 = wiki_base + "/w/index.php?title=Category:Video_game_lists_by_platform&pagefrom=Windows%0AIndex+of+Windows+games+%28P%29#mw-pages"
api_base = wiki_base + "/w/api.php?action=query&prop=revisions&rvprop=content&format=json&titles="

synonyms = { "3DO Interactive Multiplayer" : "3DO",  "Enix home computer" : "Enix",
             "Nintendo Entertainment System" : "NES", "Super Nintendo Entertainment System" : "SNES",
             "Nintendo GameCube" : "GameCube", 
             "PC Engine CD" : "TurboGrafx-16", "PC Engine" : "TurboGrafx-16", "Windows" : "PC",
             "Windows Mobile Professional" : "Pocket PC", "PlayStation Portable" : "PSP",
             "PlayStation 2" : "PS2", "PlayStation 3" : "PS3", "PlayStation 4" : "PS4",
             "Macintosh" : "Mac", "PlayStation Vita" : "Vita" }

genres = ["educational", "adventure", "golf", "shogi", "role-playing", "open-world", "shooter", "trading",
          "visual novel", "puzzle", "shoot -em up", "shoot em up", "racing", "maze", "card", "tennis", "strategy",
          "football", "sports", "interactive fiction", "action", "platform", "stealth", "chess", "pinball"]

# functions

def unwiki(text, quiet = True):
    """convert text in wiki markup format, into plain text"""
    # remove html comments
    while ("<!--" in text or "-->" in text):
        text = (text[: text.find("<!--")]    if "<!--" in text else "") + \
               (text[text.find("-->") + 3 :] if "-->" in text else "")
    quiet or print(text)

    # remove tags, but keep inner contents
    tags = ["sup", "small", "s", "center"]
    for tag in tags:
        bgn = "<" + tag + ">"
        end = "</" + tag + ">"
        if (bgn in text and end in text):
            text = text.replace(bgn, " ").replace(end, " ")
    
    # remove tags
    tags = ["ref", "br", "span", "sup", "small", "center", "s", "div"]
    tags = tags + list(map(lambda s: s.upper(), tags))
    for tag in tags:
        while "<" + tag in text:
            # use sorted instead of list to get first match appearing in string
            end  = (list(map(lambda s: text.find(s) + len(s), filter(lambda s: s in text,
                        ["</" + tag + ">", tag + ">", "/>", ">"]))))[0]
            orig = text
            text = text.replace(text[text.find("<" + tag) : end], "" if tag != "br" else "\n")

            # if no change, then try another tag, return to this tag later
            if (orig == text):
                tags.append(tag)
                break

    # remove standalone tags
    for tag in tags:
        text = text.replace("</" + tag + ">", "")

    if ("<" in text and ">" in text):
        print("unknown tag? " + text[text.find("<") : text.find(">") + 1])
            
    quiet or print(text)
        
    # remove style attributes
    styles = ["style", "width", "scope", "rowspan", "colspan", "class", "align"]
    for style in styles:
        if style + "=" in text:
            ind = text.find(style)
            end = text.find("|", ind)
            text = text.replace(text[ind : end + 1], "") if end != -1 else \
                   text.replace(text[ind :], "")
    quiet or print(text)

    # balance template brackets
    if balanceof(text) != 0:
        text += "}}" * (balanceof(text))
    
    # remove templates
    temps = ["Anchor", "Citation needed"]
    for temp in temps:
        if temp in text and "{{" in text and "}}" in text:
            ind = text.find(temp)
            text = text.replace(text[text.rfind("{{", 0, ind) :
                                     text.find("}}", ind) + 2], "")
    quiet or print(text)
        
    # remove italics, bold
    if ("''" in text or "\\'\\'" in text):
        text = text.replace("'''", "").replace("''", "") \
                   .replace("\\'" * 3, "").replace("\\'" * 2, "")
    quiet or print(text)
    
    # remove link targets
    while ("[[" in text and "|" in text and "]]" in text):
        # find first "|" bar inside of "[[" "]]" brackets
        ind = lnk = end = -1
        while (lnk == -1 or end == -1):
            ind = text.find("|", ind + 1)
            lnk = text.rfind("[[", text.rfind("]]", 0, ind) + 1, ind)
            end = text.find("]]", ind)

            # end if no more "|" bar characters
            if (ind == -1):
                break
        if (ind == -1):
            break

        # regular target link, so remove target
        if (not text[ind : ind + 3] == "|]]"):
            text = text[: lnk ] + text[ind + 1 : end] + text[end + 2 :]

        # automatic target link
        else:
            # remove text in parentheses
            if (text.rfind("(", 0, ind) != -1):
                text = text[: text.rfind("(", 0, ind)].strip() + \
                       text[text.rfind(")", 0, ind) + 1 :].strip()

            # remove text after comma
            if (text.rfind(",", 0, ind) != -1):
                text = text[: text.rfind(",", 0, ind)].strip() + \
                       text[ind + 1 :].strip()

            # remove "Wikipedia:" marker
            if (("Wikipedia:") in text):
                text = text.replace("Wikipedia:", "")
            text = text[: ind] + text[ind + 1 :]
    quiet or print(text)
    
    # remove links
    if (("[[") in text and ("]]") in text):
        text = text.replace("[[", "").replace("]]", "")
    quiet or print(text)

    # remove link with single bracket
    if (("[") in text and ("]") in text):
        m = re.search("(.*)\[[^\s]+\s*(.+)\](.*)", text)
        if (m):
            text = "".join(m.groups())
        
    # replace misc templates - vgy, Nihongo, date, color, flagicon, excl, etc
    regexes = [r'[V|v]gy\|\|?(\d{4})(?:\|\d{4})?',
               r'[V|v]gy\|[T|t][B|b][A|a]',
               r'[N|n]ihongo\|(.*?)\|[^\{\}]*',
               r'[D|d]ts\|(\d{4}.\d{1,2}.?\d{1,2}?)',
               r'[D|d]ts\|([\w\s\d,-| ]*)',
               r'[D|d]ate\|([\w\d\s-]+)\|?.*?',
               r'[C|c]olor\|.*?\|([^\{\}]*)',
               r'[F|f]lagicon\|([^\}]+)',
               r'[N|n]owrap\|(.*?)',
               r'([Y|y]es|[N|n]o|[P|p]artial|[M|m]aybe|dunno|Y|N|y|n)\|?.*?',
               r'(Cancelled)\|?.*?',
               r'[S|s]ort\|.*?\|([^\{\}]*)',
               r'[R|r]ef.*?\|.*(?:\|[^\{\}]*)?',
               r'((?:[N|n]/?[A|a])|(?:[T|t][B|b][A|a]).*?)',
               r'(?:AUS|BRA|CAN|GER|ESP|EU|FIN|FRA|JPN|UK|USA)',
               r'#time:.*?,\s*.\|([\w\d\s-]*)',
               r'[S|s]tart [D|d]ate\|(.*?)',
               r'[V|v]grelease\|([^=]*)=([^=]*?)',
               r'[V|v]grelease\|' + '([^=]*)=([^=]*?\|?)' * 2,
               r'[V|v]grelease\|' + '([^=]*)=([^=]*?\|?)' * 3,
               r'[V|v]grelease\|(.+?)\|(.+)',
               r'[V|v]grtbl(?:-tx|-bl)?\|?(.*?)',
               r'[V|v]grelease new\|v=\d\|(.*?)',
               r'[V|v](?:grelease|ideo game release)\|(.*)=(.*?)',
               r'([C|c]heck mark)\|.*?',
               r'([C|c]ross)\|.*?',
               r'[P|p]lainlist\|(.*?)\s*',
               r'[T|t]ooltip\s*\|(.*)(?:\|.*?)?',
               r'[A|a]bbr\|(.*?)',
               r'[D|d][i|n][s]?.*?',
               r'[C|c]itation [N|n]eeded(?:\|.*?)?',
               r'[C|c]ite[^\{\}]*?',
               r'[N|n]ot a typo\|(.*?)',
               r'[U|u]nknown',
               r'[R|r]eflist',
               r'[V|v]ideo game lists by platform']

    for regex in regexes:
        r = re.compile(r'(.*)\{\{\s*' + regex + '\s*\}\}(.*)', re.DOTALL | re.I)

        # remove all occurences of template
        while (re.match(r, text)):
            m = re.match(r, text)
            delim = "" if not "[D|d]ts" in regex and not "[V|v]gr" in regex else " "
            text = delim.join(m.groups())
            quiet or print(text)

    # remove all unknown templates
    while ("}}" in text and "{{" in text):
        temp = text[text.find("{{") : text.find("}}") + 2]
        text = text.replace(temp, "")
        print("unknown template? " + temp)
        if temp == "":
            break

    # convert escape characters and unicode characters
    while ("\\" in text):
        ind = text.find("\\")
        if (text[ind + 1] == "n"):
            text = text.replace("\\n", "\n")
        if (text[ind + 1] == "t"):
            text = text.replace("\\t", " ")
        elif (text[ind + 1] == "u"):
            code = text[ind + 2 : ind + 6]
            text = text.replace("\\u" + code, chr(int(code, 16)))
        elif (text[ind + 1] == "'"):
            text = text.replace("\\'", "\'")
        elif (text[ind + 1] == "\""):
            text = text.replace("\\\"", "\"")
        elif (text[ind + 1] == "\\"):
            text = text.replace("\\\\", "\\")
        else:
            print("error: " + text[ind + 1])
            raise BaseException
    quiet or print(text)
    
    # combine multiple lines, using bar character
    text = " | " .join(t.strip() for t in text.splitlines() if t.strip() != "") \
           if text != "" else ""
    quiet or print(text)
    
    # reorder text if in "xxx, The yyy" format
    text = reorder(text)
    quiet or print(text)

    # eliminate redundant spacing
    text = re.sub(r'\s+', " ", text)

    # eliminate irregular characters
    text = text.replace("•", "")
    
    return text.strip()

def reorder(text):
    """reorder strings that are in format [title, The] to [The title]"""
    m = re.match("(.+), The(.*)", text)
    return "The " + m.group(1) + m.group(2) if m else text

def balanceof(text):
    """find bracket balance of string, return 0 if balanced, else diff in brackets"""
    return text.count("{{") - text.count("}}")

def bar_split(text):
    """split text divided by wiki formatted bars, into list of strings"""
    strs = []
    while (True):
        # use non-greedy matching to find first bar separator, from the left
        m = re.search(r'(.+?)([\|]{2,})(.+)', text)
        if (m is None):
            # if no bar separator found, then return w/ remaining text
            strs.append(text)
            return strs
        else:
            # split up text
            strs.append(m.group(1))
            bars = m.group(2)
            text = m.group(3)

            # append blank entries, if multiple bar separators (ex: abc |||| def)
            lenb = len(bars) // 3 - 1 if (len(bars) % 3 == 0) else \
                   len(bars) // 2 - 1 if (len(bars) % 2 == 0) else 0
            strs += [""] * lenb            

def year(text):
    """interpret date data, convert to mm/dd/yyyy format"""
    # if multiple lines given, then find year for each line
    if (len(text.splitlines()) > 1):
        return " | ".join([year(t) for t in text.splitlines() if t != ""])
    if (len(text.split(" | ")) > 1):
        return " | ".join([year(t) for t in text.split("|") if year(t) != ""])

    # extract region information
    regions = re.search(r"(([A-Z]{2,3}(, )?)+)", text)
    reg_str = " " + regions.group(1) if regions else ""

    # search for date in format: 10/21/2003
    date0 = re.search(r"(\d+)/(\d+)/(\d+)", text)
    if (date0):
        return date0.group(3) + reg_str

    # search for date in format: October 21, 2003
    date1 = re.search(r"(\w+) (\d+), (\d+)", text)
    if (date1):
        return date1.group(3) + reg_str

    # search for date in format: October 2003
    date1a = re.search(r"([A-Z|a-z]+)\s+(\d{4})", text)
    if (date1a):
        return date1a.group(2) + reg_str

    # search for date in format: 2003-10-21
    date2 = re.search(r"(\d+)[^\d](\d+)[^\d](\d+)", text)
    if (date2):
        return date2.group(1) + reg_str

    # search for date in format: 2003
    date3 = re.search(r"(\d{4})", text)
    if (date3):
        return date3.group(1) + reg_str

    # if date not parsed, then return empty string
    return ""

def country(text):
    """find country that text is referring to"""
    if   (("NA") in text or ("North America") in text):
        return "NA"
    elif (("JP") in text or ("Japan") in text):
        return "JP"
    elif (("AS") in text or ("Asia") in text):
        return "AS"
    elif (("EU") in text or ("Europe") in text):
        return "EU"
    elif (("PAL") in text):
        return "PAL"
    elif (("AU") in text or ("Australia") in text or ("Australasia") in text):
        return "AU"
    elif (("BR") in text or ("Brazil") in text):
        return "BR"
    elif (("SK") in text or ("South Korea") in text):
        return "SK"
    elif (("WW") in text or ("World") in text):
        return "WW"
    elif (("INT") in text or ("International") in text):
        return "INT"
    else:
        return ""

def update_games(games, name, dat, col):
    """update games info with new data, return games and name"""
    low = col.lower()
    if (name == ""):
        # set the name of game, add a new blank entry to games dictionary
        if ((low.find("title") != -1 or low.find("name") != -1 or
             low.find("game") != -1) and dat != "—"):
            name = reorder(dat)
            (games, name) = parse_name(games, name)
    else:
        t = dat
        yr = year(t)
        cn = country(col)

        # add year in which game was released
        if ((low.find("year") != -1 or low.find("date") != -1 or
             col == "Release" or col == "Released" or col == "First released") and
            (cn == "")):
            games[name][0] += yr + " | "

        # add game genre
        if (low.find("genre") != -1):
            games[name][1] = t

        # add game developers / programmers
        if (low.find("develop") != -1 or low.find("program") != -1):
            games[name][2] = t

        # add publishing company, or multiple companies separated by bar
        if (low.find("publish") != -1):
            games[name][3] = " | ".join( \
                [t.strip() for t in dat.split("\n") if t != ""])

        # add regions in which game was released
        if (low.find("region") != -1):
            games[name][5] = t

        # add rating information, can be from multiple standards
        if (low.find("esrb") != -1 or low.find("pegi") != -1 or \
            low.find("cero") != -1 or low.find("acb")  != -1):
            games[name][6] += col + " " + t + ", "

        # find misc info / details about a game
        if (low.find("details") != -1 or low.find("description") != -1):
            g = [genre for genre in genres if genre in t.lower()]
            if (not(len(g) < 1)):
                games[name][1] += g[0]

        # check if 'yes' or checked or release date given
        if (yr != "" or t.lower() == "yes" or "check mark" in t.lower()):
            if (cn != ""):
                games[name][5] += cn + ", "
                if (yr != ""):
                    games[name][0] += yr + " " + cn + " | "
    return (games, name)

def parse_name(games, name, title = ""):
    """parses game title for extra info: year, regions. add blank entry to games dict"""
    year = ""
    dev = ""
    regions = ""
    group = m = ""

    # look for all extra information in parantheses
    while (group != None and m != None):
        m = re.search(r'(.*)\(([^\(\)]+)\)(.*)', name)
        if (m):
            name = m.group(1).strip() + " " + m.group(3).strip()
            group = m.group(2).strip()
            if (group):
                # add region info
                if (re.match(r'([A-Z]{2}\s*)+', group)):
                    regions = ", ".join(group.split())

                # add release date info
                elif (re.match(r'[0-9]{4}', group)):
                    year = group

    # create a new blank entry for the game
    games[name] = ["",] * 7
    games[name][0] = year
    games[name][2] = dev
    games[name][3] = dev
    games[name][4] = title
    games[name][5] = regions
    return (games, name)

def post_process(games, name, title):
    """add title to platforms data, remove trailing comma"""
    # add platform info
    games[name][4] += title

    # remove trailing bar from release date info
    if (games[name][0] [-2:-1] == "|"):
        games[name][0] = games[name][0] [0:-3]

    # remove trailing comma from region info
    if (games[name][5] [-2:-1] == ","):
        games[name][5] = games[name][5] [0:-2]

    # remove trailing comma from rating info
    if (games[name][6] [-2:-1] == ","):
        games[name][6] = games[name][6] [0:-2]
    return (games, name)

def write_db(games, name, f = None):
    """write games data of [name] to file, tab separated, if opened"""
    # if file is given, not None, then write to file
    if (f):
        if (name != ""):
            # file contents are tab separated, and marked with null if unknown
            data = [name,] + [d if d != "" else "null" for d in games[name]]
            f.write("\t".join(data) + "\n")
        else:
            print("empty name")

    # else, print out data for debugging
    else:
        data = [name,] + [d if d != "" else "null" for d in games[name]]
        print("\t".join(data))
    
def get_platforms(url):
    """get a list of elements w/ platform data from wiki url"""
    d = pq(url)
    c = d("div#mw-pages")("div.mw-content-ltr")
    return [l.find("a") for l in c("li")]

def mine_wiki_page(title, url, f = None):
    """mine wiki page for list of video games, return dict of game info"""
    # games = { name : (year, genre, dev, pub, platforms, regions, rating) }
    print("mining: " + title)
    response = urllib2.urlopen(url, timeout = 30)
    html = str(response.read())
    header = ""
    endcol = False
    gmlist = False
    islist = False
    table = False
    unbal = False
    rowsp = 0
    games = {}
    data = cols = []
    list_of_games = re.compile(r'list of.*games')
    
    for line in html.split("\\\\n"):
        # if end of table row, then add data to dict
        if table and data != [] and (line.startswith("|-") or line.startswith("|}")):
            name = ""

            # account for rowspan by adding blank columns for each row
            if (rowsp != 0):
                data = [""] * (len(cols) - len(data)) + data
                rowsp -= 1

            # update games dict
            for dat, col in zip(data, cols):
                #print(col + ": " + unwiki(dat))
                (games, name) = update_games(games, name, unwiki(dat), col)

            # if preceding header contains year info, then update w/ year info
            if year(header) != "" and year(header) not in games[name][0]:
                (games, name) = update_games(games, name, header, "Year")

            # do post processing, then write to database file
            if (name != ""):
                (games, name) = post_process(games, name, title)
                write_db(games, name, f)
            data = []
            unbal = False

        # blank line
        if (len(line) < 1):
            continue

        # detect start of wikitable
        elif "class" in line and "wikitable" in line:
            print("table found")
            table = True
            endcol = False
            cols = []

        # detect wikitable column declarations
        elif line.startswith("!") and table and not endcol:
            cols += map(unwiki, line.strip("!|").split("!!"))

        # end of table column
        elif line.startswith("|}") or \
             (line.startswith("|-") and "sortbottom" in line):
            print("table end")
            table = False
            unbal = False
        elif line.startswith("|-") and table:
            if cols != [] and not endcol:
                endcol = True
                print("cols: " + str(cols))
            continue

        # skip table caption
        elif line.startswith("|+") and table:
            continue

        # skip table header
        elif line.startswith("||") and table:
            continue

        # row entry, can start with "|" or "!" if not in column declaration
        elif (line.startswith("|") or line.startswith("!")) and \
             table and endcol and not unbal:
            if ("rowspan" in line):
                m = re.match(r'.*rowspan\s*=\s*\"?(\d+)\"?\s*.*', line)
                rowsp = int(m.group(1)) if m else 0
            if ("colspan" in line):
                m = re.match(r'.*colspan\s*=\s*\"?(\d+)\"?\s*.*', line)
                colsp = int(m.group(1)) if m else 0
                line = line.strip("|").strip()
                line = line + ("||" + line) * (colsp - 1)

            # add all lines to data list
            data += bar_split(line.strip("|").strip("!"))

            # determine if brackets in line are unbalanced
            unbal = balanceof(line) != 0
        elif line.startswith("*") and table:
            data[len(data) - 1] += " ".join(bar_split(line.strip("*"))) + " "
        
        # parse headings
        elif line.startswith("=") and line.endswith("="):
            header = line.strip("=")
            hlower = header.lower()
            #print("header: " + header)
            if ("see also" in hlower or "references" in hlower or
                "external links" in hlower or "footnote" in hlower or
                "update notes" in hlower):
                print("unstructured list end")
                gmlist = False

        # parse list
        elif "{{Div col}}" in line:
            print("list start")
            islist = True

        # parse list item
        elif line.startswith("*") and islist and not table:
            (games, name) = parse_name(games, unwiki(line[1:]), title)
            write_db(games, name, f)
            #print(name)

        # parse end of list
        elif ("{{Div col end}}") in line:
            print ("list end")
            islist = False

        # parse unstructured list
        elif "list" in line and re.search(list_of_games, unwiki(line)):
            print("unstructured list start")
            gmlist = True

        # parse unstructured list entry
        elif line.startswith("*") and gmlist and not table and \
             not "Exclus" in header:
            if len(line.split(" ")) < 10 and not "only release" in line:
                (games, name) = parse_name(games, unwiki(line[1:]), title)
                write_db(games, name, f)
                #print(name)

        # otherwise, assume line is part of previous col and append data
        elif table and data != []:
            data[len(data) - 1] += line
            unbal = balanceof(data[len(data) - 1])

    return games

def mine_wiki(f = None, skip_to = None, end_at = None):
    """mine wikipedia list of all video games for info"""
    # games dict: { name : (year, genre, dev, pub, platforms, regions, rating) }
    pfs = get_platforms(wiki) + get_platforms(wiki2)
    titles = []
    games = {}
    for pf in pfs:
        title = pf.text

        # ignore combined pages, redundant pages
        if (title == "List of Commodore 64 games" or
            title == "List of Amiga games" or
            title == "List of PC video games" or
            title == "List of free PC titles"):
            continue
        if (title.find("Super Famicom and Super Nintendo") != -1):
            continue
        if (title.find("network") != -1 or title.find("multiplayer") != -1 or
            title.find("exclusive") != -1 or title.find("downloadable") != -1):
            continue
        if (title.find("CD-ROM") != -1 or title.find("DVD-9") != -1):
            continue
        if (title.find("arcade video games:") != -1):
            continue
        if (title.find("Gamesharing") != -1 or
            title.find("trackball") != -1 or
            title.find("System Link") != -1 or
            title.find("Move") != -1 or
            title.find("Games with Gold") != -1 or
            title.find("Xbox One applications") != -1 or
            title.find("3D PlayStation") != -1 or
            title.find("Draft") != -1 or
            title.find("Kinect") != -1):
            continue

        # strip prefix
        if (title.startswith("List of")):
            title = title.replace("List of", "").lstrip()
        elif (title.startswith("Index of")):
            title = title.replace("Index of", "").lstrip()
        elif (title.startswith("Draft")):
            continue        # empty page, ignore
        elif (title.startswith("Chronology")):
            continue        # redundant, ignore (Chronology of Wii games)
        else:
            # ignore kinect fun labs, platinum hits
            continue

        # remove parenthesis / colon for subcategories
        if (title.find(":") != -1):
            title = title[: title.find(":")]
        if (title.find(")") != -1):
            title = title[: title.find("(") - 1]

        # independent dreamcast games, other labels, etc
        title = title.replace("commercially released independently developed", "")
        title = title.replace("commercial", "")
        title = title.replace("free", "")
        title = title.replace("unlicensed and prototype", "")
        title.lstrip()

        # strip suffix
        if (title.endswith("video games")):
            title = title.replace("video games", "").rstrip()
        elif (title.endswith("games")):
            title = title.replace("games", "").rstrip()
        elif (title.endswith("titles")):
            title = title.replace("titles", "").rstrip()
        elif (title.endswith("software")):  # wii u software
            title = title.replace("software", "").rstrip()
        elif (title.endswith("applications")):
            title = title.replace("applications", "").rstrip()
        elif (title.startswith("games for the original")): # Game Boy
            title = title.replace("games for the original", "").lstrip()
        elif (title.startswith("Xbox games on")): # xbox 360 kinect games
            title = title.replace("Xbox games on", "").lstrip()
        elif (title.find("Virtual Console games for") != -1):
            title = "Virtual Console"
        else:
            # ignore eye toy, exclusives, conversions, accessories, etc
            continue

        # find synonyms for game titles
        if title in synonyms:
            title = synonyms[title]

        if not title in titles:
            titles.append(title)

        # if skip_to parameter given, then stop skipping when reached the desired title
        if skip_to is not None and skip_to == title:
            skip_to = None

        # if end_to parameter given, then stop mining when given title is reached
        if end_at is not None and end_at == title:
            return games

        if not skip_to:
            # for arcade games, visit all subpages instead of parsing main page
            if (title == "arcade"):
                for sub in ["0..9",] + [chr(i) for i in range(65, 91)] + ["Not_released",]:
                    games.update(mine_wiki_page(title, api_base + pf.attrib['href'][6:] + ":_" + sub, f))

            # otherwise, use wiki api to find game info, by reading wiki markup source
            else:
                games.update(mine_wiki_page(title, api_base + pf.attrib['href'][6:], f))
            f is None or f.write("\n\n\n")

    print(titles)
    return games

if __name__ == "__main__":
    f = open('games.txt', 'a', encoding = 'utf-8')
    games = mine_wiki(f, skip_to = None)
    f.close()
    pass
