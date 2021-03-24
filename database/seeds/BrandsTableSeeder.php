<?php

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{

    public array $brands = ["Quicksilver", "Billabong", "Hurley", "O’Neill", "RVCA", "Vans", "Volcom", "Reef", "Roxy",
    "Ripcurl", "Channel Islands Surfboards", "Nixon", "Patagonia", "Dakine", "Sanuk",
    "Critical Slide Society", "Globe", "Vissla", "Insight 51 ", "Outerknown", "Rusty", "FCS",
    "Futures Fin", "Stussy", "Firewire Surfboards", "Deus Ex Machina", "Oakley", "Von Zipper ",
    "Xcel Wetsuits ", "Ocean & EarthLightening Bolt", "Bertram", "Boston Whaler", "Chaparral",
    "Grady-White", "Lund", "MasterCraft", "Sea Ray", "Tracker", "Yamaha", "Viking yachts",
    "Everlast", "Venum", "Ufc", "Ringside", "Title", "Century", "Combat sports", "Adidas",
    "Hayabusa", "Pure boxing", "Black diamond", "Petzl", "Mammut", "Edelrid", "Trango", "La sportiva",
    "Mad rock", "6a", "Alpen pass", "Alpkit", "Andrea boldrini", "Acr’teryx", "Asana", "Austrialpin",
    "Beal", "Big wall gear", "Black diamond equipment", "Blue ice", "Blue water ropes", "Boreal",
    "Bufo", "Butora", "C.a.m.p.", "Cassin", "Climb x", "Climbing technology", "Climbtech", "Cmi",
    "Corazon", "Cypher", "Dmm", "Eb", "E-climb", "Edelrid", "Edelweiss", "Eliteclimb", "Evolv",
    "Faders ", "Fixehardware", "Firn line design", "Fish", "Fiveten", "Flashed", "Flipp",
    "Furnace industries", "Fusion", "Gadd", "Gilmonte", "Gipfel climbing equipment", "Grandwall",
    "Grivel", "Howey tool", "Ice rock", "Jurax", "Kailas", "Kong", "Kouba", "Krukonogi", "Kush climbing",
    "La sportiva", "Lacd", "Lavan", "Lhotse", "Lowa", "Mad rock", "Mammut", "Maxim", "Metolius",
    "Millet", "Milo", "Misty mountain", "Moon climbing", "New england ropes", "Obrworks", "Ocun",
    "Omega pacfific", "Organic crash pads", "Petzl", "Pmi", "Red chili", "Roca topes", "Rockstone",
    "Rock empire", "Rock exotica", "Runout customs", "Salewa", "Saltic", "Scarpa", "Simond",
    "Singing rock", "Skylotec", "Smc", "Snake", "Snap climbing", "So iii", "Spotter", "Sterling",
    "Stubai", "Suluk 46", "Teknia", "Tenaya", "Tendon", "Totem", "Trango", "Trangoworld", "Triop",
    "Trongau", "Tufa climbing", "Valley giant", "Vline", "Voodoo", "Wild climb", "Wild country",
    "Wired bliss", "Yates", "Assos", "Attaquer", "Bell", "Bern", "Black sheep cycling", "Bmc",
    "Cadence", "Café du cycliste", "Cpreme", "Giro", "Gotrax", "God and famous", "Louis garneau",
    "Nickelodeon", "Oro", "Oakley", "Paragon", "Raskullz", "Rei", "Dick’s sporting goods", "Maap",
    "Ornot", "Pedla", "Podia", "Rapha", "Schwinn", "Search and state", "Smith optics", "Triple eight",
    "Velocia", "Void", "Volero", "Gt schwinn signature", "Pacific cycle", "Ybike", "Kulana", "Mongoose",
    "Trek", "Bmc", "Scott", "Felt ", "Shimano", "Giro", "Nike", "Poc", "The pedla", "Café du cycliste",
    "Rapha", "Sigrkirschner brasil", "Bernard", "Mavic cycling", "Assos ", "Castelli", "Capo",
    "Pas normal studios", "Chpt", "Brandt-sorenson ", "Attaquer", "Volero", "Sqd athletica",
    "Cadence", "God & famous", "Rapha", "Pas normal studios", "Siroko", "Chapt3", "Santini", "Sportful",
    "Ashmei", "Giordana", "Garneau", "Pearl izumi", "Gore", "Gore wear", "Bianchi milano", "Nalini",
    "Twin six", "De marchi", "Pedal mafia", "Campagnolo", "Dhb", "Attaquer", "Capola passione",
    "Shimano", "Specialized", "Bontrager", "Pedal ed", "Morvelo", "Kalas", "Ornot", "Velocio", "Ashmei",
    "Velobici", "Alba optics", "Alessandro Albanese", "Allon", "Animo", "Ariat", "Arista",
     "Asmar Equestrian", " ", "B Vertigo ", "Barbour ", "Beacon Hill ", "Bette & Court ", "Beval ",
     "BHW ", "Black Oak ", "Brangier ", "Brookside", "C4 ", "Callidae ", "Cambrai Collection ",
     "Cavalleria Toscana ", "Cavallo ", "Celeris ", "Centaur ", "Champion Tails", "Cheval", "Circuit",
     "Clever Human ", "Columbia ", "CWD", "Dada Sport ", "Dainese ", "DeNiro ", "Devon Aire ",
     "Devon-Aire ", "Dover Saddlery ", "Dubarry ", "Dy'on", "Edgewood ", "EGO7 ", "EIS ",
     "El Estribo", "Elite", "eq girl", "eqGirl", "Equifit", "Equiline", "Equine Couture", "Equistar",
     "Equitex", "EquiVisor ", "Equo", "Eric and Lani ", "Essex Classics", "Eurostar", "Fabbri",
     "Falconhead ", "Fior da Liso ", "FITS", "Fleeceworks ", "Foothuggies ", "For Horses",
     "Fruit of the Loom", "Gand Prix ", "Gap Kids ", "Gersemi ", "Goode Rider ", "GPA ", "Grand Prix",
     "HABiT ", "Hadfield's ", "Harcour ", "Hayward Sportswear ", "Henri Lloyd ", "Hermes ", "Heythrop ",
     "Horseware ", "Horze ", "Hunt Club ", "Hunter ", "Huntley Equestrian", "Iago ",
     "International Boot Co. ", "Irideon ", "Ital-Design ", "It's a Haggerty's", "Jaipur Polo Company ",
     "Jerico ", "Jose Gonzales Leather ", "JOTT ", "Joules ", "Justin", "K. Marie Equestrian ",
     "Kastel ", "Kerrits ", "Kingsland ", "Kjus", "KL Select", "La Mundial ", "Lacoste ", "Le Fash ",
     "Lettia ", "Lo Ride ", "Lo-Ride ", "Lovestitch ", "Lucchese ", "Lululemon", "M. Toulouse ",
     "Manfredi ", "Mantis ", "Marigold Riding Apparel ", "Mastermind ", "Michael and Kenzie 1911",
     "Mountain Horse ", "Mudd", "New Cavalry ", "Niavo Equestrian ", "Nike ", "Noble Outfitters ",
     "North End ", "Norton ", "Nunn Finer", "Oakbark and Chrome ", "Ogio ", "On Course ", "Ovation",
     "Parlanti ", "Pat Areias ", "Patricia Wolf ", "People On Horses ", "Pessoa ", "Phyllis Stein ",
     "Pikeur ", "Plymouth ", "Polo Ralph Lauren ", "Professional's Choice", "Qatar", "Ralph Lauren ",
     "Ralph Lauren Sport", "Rambo", "Rebecca Ray Designs", ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->brands as $brand) {
            $brandUrl = Str::slug($brand, '-');
            Brand::updateOrCreate(['slug' => $brandUrl], ['name' => $brand, 'slug' => $brandUrl]);
        }
    }
}
