<?php
    require_once __DIR__."/../app/database/db.class.php";
    require_once __DIR__ . '/user.class.php';

    class Service
    {
        function quackoviPoDatumu($username)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT quack,date FROM dz2_quacks INNER JOIN dz2_users WHERE dz2_quacks.id_user = dz2_users.id AND username = :username');
            $st->execute(['username' => $username]);

            $quackovi = [];
            // dohvati quackove po datumu
            while($row = $st->fetch())
            {
                $quackovi[] = array( 'quack' => $row['quack'], 'date' => $row['date'] ) ;
            }

            // treba ih sortirati po datumu !!!!!!!!!!!!!!!!!!!!!!11
            return $quackovi;
        }
        function allFollowers($username)
        {
            // dohvati sve followere za dani username
            $db = DB::getConnection();

            $st = $db->prepare('SELECT id FROM dz2_users WHERE username = :username');
            $st->execute(['username' => $username]);
            if( $row = $st->fetch() )
            {
              $id = $row['id'];
            }

            $st = $db->prepare('SELECT username FROM dz2_users INNER JOIN dz2_follows WHERE dz2_users.id = dz2_follows.id_followed_user AND id_user = :id');
            $st->execute(['id' => $id]);

            $followers = [];
            while($row = $st->fetch())
            {
                $followers[] = array( $row["username"]);
            }
            return $followers;
        }

        //----------------------------------------------------------------------
        function terminiZaOdredjeniDan($id, $datum)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_dvorane INNER JOIN projekt_raspored WHERE projekt_dvorane.id = projekt_raspored.dvorana AND id_film = :id AND datum = :datum');
            $st->execute(['id' => $id, 'datum' => $datum]);

            $termini = [];
            while($row = $st->fetch())
            {
                    $termini[] = [$row['termin'], $row['dvorana'], $row['tip']];
            }

            return $termini;

        }
        function filmPoImenu($ime)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_filmovi WHERE ime = :ime');
            $st->execute(['ime' => $ime]);

            if($row = $st->fetch())
            {
                $film = new Film($row['id'], $row['ime'], $row['redatelj'], $row['zanr'], $row['trajanje'], $row['godina'],
                    $row['drzava'],$row['pocetak_prikazivanja'], $row['sadrzaj'], $row['posebna_oznaka'], $row['video']);

                return $film;
            }
            else
            {
                return NULL;
            }


        }

        function filmPoId($id)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_filmovi WHERE id = :id');
            $st->execute(['id' => $id]);

            $row = $st->fetch();

            $film = new Film($row['id'], $row['ime'], $row['redatelj'], $row['zanr'], $row['trajanje'], $row['godina'], $row['drzava'],
            $row['pocetak_prikazivanja'], $row['sadrzaj'], $row['posebna_oznaka'], $row['video']);

            return $film;

        }


        function dvorane()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT DISTINCT id, tip FROM projekt_dvorane");
            $st->execute();

            $dvorane = [];
            while($row = $st->fetch())
            {
                $dvorane[] = array("id" => $row["id"], "tip" => $row["tip"]);
            }
            return $dvorane;
        }

        function odabir_dvorane($id)
        {
            $db = DB::getConnection();
            // dohvati nazive svih filmova
            $st = $db->prepare("SELECT id, ime FROM projekt_filmovi");
            $st->execute();

            $nazivi = [];
            while($row = $st->fetch())
            {
                $nazivi[$row["id"]] = $row["ime"];
            }

            $st = $db->prepare("SELECT datum, termin, id_film FROM projekt_raspored
                                WHERE dvorana = :dvorana");
            $st->execute(["dvorana" => $id]);
            $zauzeti_termini = [];
            while($row = $st->fetch())
            {
                $zauzeti_termini[] = array("datum" => $row["datum"],
                                            "termin" => $row["termin"],
                                            "naziv" => $nazivi[$row["id_film"]]);
            }
            return $zauzeti_termini;
        }

        function dodaj_termin($godina, $mjesec, $dan, $id, $id_film, $termin)
        {
            $datum = $godina."-".$mjesec."-".$dan." ".$termin;
            $novi = new DateTime($datum);
            $zauzeti_termini = $this->odabir_dvorane($id);
            foreach($zauzeti_termini as $neki)
            {
                $tmp = new DateTime($neki["datum"]." ".$neki["termin"]);
                if($tmp->format('Y-m-d H:i:s') === $novi->format('Y-m-d H:i:s'))
                    return 0;
            }
            // ako termin nije definiran, dodaj ga u bazu
            $db = DB::getConnection();
            $st = $db->prepare("INSERT INTO projekt_raspored (id_film, datum, termin, dvorana)
                                 VALUES (:id_film, :datum, :termin, :dvorana)");
            $st->execute(["id_film" => $id_film, "datum" => $datum, "termin" => $termin,
                            "dvorana" => $id]);
            return 1;
        }

        function popis_filmova()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT id, ime FROM projekt_filmovi");
            $st->execute();

            $popis = [];
            while($row = $st->fetch())
            {
                $popis[] = array("id" => $row["id"], "ime" => $row["ime"]);
            }
            return $popis;
        }

        function filmoviPoZanru($zanr)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_filmovi WHERE zanr = :zanr');
            $st->execute(['zanr' => $zanr]);

            $filmovi = [];

            while($row = $st->fetch())
            {
                $filmovi[] = new Film($row['id'], $row['ime'], $row['redatelj'], $row['zanr'], $row['trajanje'], $row['godina'], $row['drzava'],
                                        $row['pocetak_prikazivanja'], $row['sadrzaj'], $row['posebna_oznaka'], $row['video']);
            }
            return $filmovi;
        }

        function trajanje_filma($id_filma)
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT trajanje FROM projekt_filmovi WHERE id = :id");
            $st->execute(["id" => $id_filma]);
            $row = $st->fetch();
            return $row["trajanje"];
        }

        function zauzeti_termini_za_datum($id_dvorane, $godina, $mjesec, $dan)
        {
            $db = DB::getConnection();
            // odredi trajanja svih filmova
            $st = $db->prepare("SELECT id, trajanje FROM projekt_filmovi");
            $st->execute();
            $trajanje_filmova = [];
            while($row = $st->fetch())
            {
                $trajanje_filmova[$row["id"]] = $row["trajanje"];
            }

            $tmp = $godina."-".$mjesec."-".$dan;
            $datum = new DateTime($tmp);

            // odredi sve termine i koliko dugo su zauzeti
            $st = $db->prepare("SELECT termin, id_film FROM projekt_raspored
                                WHERE dvorana = :dvorana AND datum = :datum");
            $st->execute(["dvorana" => $id_dvorane, "datum" => $datum->format("Y-m-d")]);
            $zauzeti_termini = [];
            while($row = $st->fetch())
            {
                $zauzeti_termini[] = array("termin" => $row["termin"],
                                        "trajanje" => $trajanje_filmova[$row["id_film"]]);
            }

            // odredi i termine od prethodnog dana (potrebno za zasjenjivanje termina rano
            // u danu)
            $jucer = date('Y-m-d', strtotime($tmp.' -1 day'));
            $st = $db->prepare("SELECT termin, id_film FROM projekt_raspored
                                WHERE dvorana = :dvorana AND datum = :datum");
            $st->execute(["dvorana" => $id_dvorane, "datum" => $jucer]);
            $prethodni_dan = [];
            while($row = $st->fetch())
            {
                $prethodni_dan[] = array("termin" => $row["termin"],
                                        "trajanje" => $trajanje_filmova[$row["id_film"]]);
            }

            return [$zauzeti_termini, $prethodni_dan];
        }

        function zauzeti_termini_samo_za_datum($id_dvorane, $godina, $mjesec, $dan)
        {
            $db = DB::getConnection();
            // odredi trajanja svih filmova
            $st = $db->prepare("SELECT id, trajanje FROM projekt_filmovi");
            $st->execute();
            $trajanje_filmova = [];
            while($row = $st->fetch())
            {
                $trajanje_filmova[$row["id"]] = $row["trajanje"];
            }

            $tmp = $godina."-".$mjesec."-".$dan;
            $sutra = date('Y-m-d', strtotime($tmp.' +1 day'));
            $st = $db->prepare("SELECT termin, id_film FROM projekt_raspored
                                WHERE dvorana = :dvorana AND datum = :datum");
            $st->execute(["dvorana" => $id_dvorane, "datum" => $sutra]);
            $sljedeci_dan = [];
            while($row = $st->fetch())
            {
                $sljedeci_dan[] = array("termin" => $row["termin"],
                                "trajanje" => $trajanje_filmova[$row["id_film"]]);
            }

            return $sljedeci_dan;
        }

        function pocetak_prikazivanja_filma($id_filma)
        {
            $db = DB::getConnection();

            $st = $db->prepare("SELECT pocetak_prikazivanja FROM projekt_filmovi WHERE id = :id");
            $st->execute(["id" => $id_filma]);

            $row = $st->fetch();
            return $row["pocetak_prikazivanja"];
        }

        function rezerviranaSjedala($naslov, $datum, $termin, $dvorana)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_rezervacije WHERE naslov LIKE :naslov AND '.
                              'datum LIKE :datum AND termin LIKE :termin AND dvorana LIKE :dvorana');
            $st->execute(array('naslov' => $naslov, 'datum' => $datum, 'termin' => $termin, 'dvorana' => $dvorana));

            $sjedala = [];

            while($row = $st->fetch())
            {
                $sjedala[] = $row['sjedala'];
            }
            return $sjedala;
        }


        function kupljenaSjedala($naslov, $datum, $termin, $dvorana)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_ulaznice WHERE naslov LIKE :naslov AND '.
                              'datum LIKE :datum AND termin LIKE :termin AND dvorana LIKE :dvorana');
            $st->execute(array('naslov' => $naslov, 'datum' => $datum, 'termin' => $termin, 'dvorana' => $dvorana));

            $sjedala = [];

            while($row = $st->fetch())
            {
                $sjedala[] = $row['red'].strval($row['sjedalo']);
            }
            return $sjedala;
        }

        function spremiRezervaciju($username, $naslov, $datum, $termin, $dvorana, $broj_ulaznica, $sjedala, $cijena)
        {
             $db = DB::getConnection();

             $st = $db->prepare('INSERT INTO projekt_rezervacije(username, naslov, datum, termin, dvorana, '.
                                 'broj_ulaznica, sjedala, cijena) VALUES (:username, :naslov, :datum, '.
                                  ':termin, :dvorana, :broj_ulaznica, :sjedala, :cijena)');
             $st->execute(array('username'=>$username, 'naslov'=>$naslov, 'datum'=>$datum, 'termin'=>$termin, 'dvorana'=>$dvorana,
                                'broj_ulaznica'=>$broj_ulaznica, 'sjedala'=>$sjedala, 'cijena'=>$cijena));

             $id = $db->lastInsertId();
             return $id;
        }

        function spremiRacun($proizvod, $kolicina, $cijena)
        {
             $db = DB::getConnection();

             $st = $db->prepare('INSERT INTO projekt_racuni(proizvod, kolicina, cijena) VALUES
                                (:proizvod, :kolicina, :cijena)');
             $st->execute(array('proizvod'=>$proizvod, 'kolicina'=>$kolicina, 'cijena'=>$cijena));

             $id = $db->lastInsertId();
             return $id;
        }

        function spremiUlaznicu($id_racun, $username, $naslov, $datum, $termin, $dvorana, $red, $sjedalo, $cijena)
        {
             $db = DB::getConnection();

             $st = $db->prepare('INSERT INTO projekt_ulaznice(id_racun, username, naslov, datum, termin, dvorana, '.
                                 'red, sjedalo, cijena) VALUES (:id_racun, :username, :naslov, :datum, :termin, :dvorana, '.
                                 ':red, :sjedalo, :cijena)');
             $st->execute(array('id_racun'=>$id_racun, 'username'=>$username, 'naslov'=>$naslov, 'datum'=>$datum, 'termin'=>$termin,
                                'dvorana'=>$dvorana, 'red'=>$red, 'sjedalo'=>$sjedalo, 'cijena'=>$cijena));

             $id = $db->lastInsertId();
             return $id;
        }

        function dvoranaPoId($id)
        {
             $db = DB::getConnection();

             $st = $db->prepare('SELECT * FROM projekt_dvorane WHERE id LIKE :id');
             $st->execute(['id' => $id]);

             $row = $st->fetch();

             $dvorana = new Dvorana($row['id'], $row['broj_redova'], $row['broj_sjedala'], $row['tip']);

             return $dvorana;
        }

        function obrisi_termin($id_dvorane, $datum, $termin)
        {
            $db = DB::getConnection();
            $st = $db->prepare("DELETE FROM projekt_raspored WHERE datum = :datum AND
                                termin = :termin AND dvorana = :dvorana");
            $st->execute(["datum" => $datum, "termin" => $termin, "dvorana" => $id_dvorane]);

            $st = $db->prepare("DELETE FROM projekt_rezervacije WHERE datum = :datum AND
                                termin = :termin AND dvorana = :dvorana");
            $st->execute(["datum" => $datum, "termin" => $termin, "dvorana" => $id_dvorane]);
            return 1;
        }

        function popis_korisnika()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT username, ime, prezime, status
                                FROM projekt_korisnici");
            $st->execute();

            $korisnici = [];
            while($row = $st->fetch())
            {
                $korisnici[] = array("username" => $row["username"], "ime" => $row["ime"],
                                    "prezime" => $row["prezime"], "status" => $row["status"]);
            }
            return $korisnici;
        }

        function promijeni_status_korisnika($username, $status)
        {
            $db = DB::getConnection();
            $st = $db->prepare("UPDATE projekt_korisnici SET status = :status
                                WHERE username = :username");
            $st->execute(["status" => $status, "username" => $username]);
            return 1;
        }

        function cijena_ulaznice($termin, $trajanje, $tip)
        {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT cijena FROM projekt_cijena_termin WHERE najraniji_termin <= :termin ORDER BY najraniji_termin DESC;');
            $st->execute(["termin" => $termin]);

            $row = $st->fetch();
            $cijena = $row['cijena'];

            $st = $db->prepare('SELECT cijena_dodatak FROM projekt_cijena_trajanje WHERE min_trajanje <= :trajanje');
            $st->execute(["trajanje" => $trajanje]);

            while($row = $st->fetch())
            {
                $cijena += $row['cijena_dodatak'];
            }

            $st = $db->prepare('SELECT cijena_dodatak FROM projekt_cijena_dvorana WHERE tip_dvorane LIKE :tip');
            $st->execute(["tip" => $tip]);

            $row = $st->fetch();
            $cijena += $row['cijena_dodatak'];

            return $cijena;
        }

        function top5()
        {
            // dohvati top 5 filmova tog tjedna
            $danas = date('Y-m-d');
            $broj_dana_od_danas = '+ 7 day';
            $sedmi = date('Y-m-d', strtotime($danas . $broj_dana_od_danas));
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_raspored INNER JOIN projekt_filmovi ON projekt_filmovi.id=projekt_raspored.id_film AND projekt_raspored.datum >= :datum'
                                . ' AND projekt_raspored.datum <= :datum2 AND projekt_filmovi.posebna_oznaka = :oznaka');
            $st->execute(['datum' => $danas, 'datum2' => $sedmi, 'oznaka' => 'TOP 5']);

            $filmovi = [];

            while($row = $st->fetch())
            {
                // ne dohvaćaj one koje si već uzeo
                if(!array_key_exists($row['id'], $filmovi))
                {
                    $filmovi[$row['id']] =  new Film($row['id'], $row['ime'], $row['redatelj'], $row['zanr'], $row['trajanje'], $row['godina'], $row['drzava'],
                        $row['pocetak_prikazivanja'], $row['sadrzaj'], $row['posebna_oznaka'], $row['video']);
                }
            }

            return $filmovi;
        }

        function rezervacije()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT * FROM projekt_rezervacije");
            $st->execute();

            $rezervacije = [];
            while($row = $st->fetch())
            {
                $rezervacije[] = array("id" => $row["id"],"username" => $row["username"], "naslov" => $row["naslov"], "datum" => $row["datum"],
                "termin" => $row["termin"], "dvorana" => $row["dvorana"], "broj_ulaznica" =>$row["broj_ulaznica"], "sjedala" => $row["sjedala"], "cijena" => $row["cijena"]);
            }
            return $rezervacije;
        }

        function obrisi_rezervaciju($id)
        {
            $db = DB::getConnection();
            $st = $db->prepare("DELETE FROM projekt_rezervacije WHERE id = :id");
            $st->execute(["id" => $id]);
            return 1;

        }
        function dohvatiRezervacije($username)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_rezervacije WHERE username = :username');

            $st->execute(['username' => $username]);

            $rezervacije = [];

            while($row = $st->fetch())
            {
                $rezervacije[] = new Rezervacija($row['id'], $row['username'], $row['naslov'], $row['datum'], $row['termin'], $row['dvorana'],
                                                    $row['broj_ulaznica'], $row['sjedala'], $row['cijena']);

            }

            return $rezervacije;
        }


        function dohvatiKupljeneUlaznice($username)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_ulaznice WHERE username = :username');

            $st->execute(['username' => $username]);

            $ulaznice = [];

            while($row = $st->fetch())
            {
                if(!array_key_exists($row['id_racun'], $ulaznice))
                {
                    $ulaznice[$row['id_racun']] = [[$row['naslov'], $row['datum'], $row['termin'],
                                $row['dvorana'], $row['red'], $row['sjedalo']]];
                }
                else
                {
                    array_push($ulaznice[$row['id_racun']],[$row['naslov'], $row['datum'], $row['termin'],
                                $row['dvorana'], $row['red'], $row['sjedalo']]);
                }
            }

            return $ulaznice;
        }

        function detaljiODvoranama()
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM projekt_dvorane');

            $st->execute();

            $dvorane = [];

            while($row = $st->fetch())
            {
                $dvorane = new Dvorana($row['id'], $row['broj_redova'], $row['broj_sjedala'], $row['tip']);

            }
            return $dvorane;
        }

        function cijenaTrajanje()
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT min_trajanje, cijena_dodatak FROM projekt_cijena_trajanje');

            $st->execute();

            $trajanje = [];

            while($row = $st->fetch())
            {
                $trajanje[] = [$row['min_trajanje'], $row['cijena_dodatak']];

            }
            return $trajanje;
        }

        function cijenaTermin()
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT cijena FROM projekt_cijena_termin');

            $st->execute();

            $termin = [];

            while($row = $st->fetch())
            {
                $termin[] = $row['cijena'];

            }
            return $termin;
        }

        function cijenaDvorana()
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT tip_dvorane, cijena_dodatak FROM projekt_cijena_dvorana');

            $st->execute();

            $dvorana = [];

            while($row = $st->fetch())
            {
                if($row['cijena_dodatak'] !== '0')
                    $dvorana[] = [$row['tip_dvorane'], $row['cijena_dodatak']];

            }
            return $dvorana;
        }

        function ulaznice()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT * FROM projekt_ulaznice");
            $st->execute();

            $ulaznice = [];
            while($row = $st->fetch())
            {
                $ulaznice[] = array("id" => $row["id"], "id_racun" => $row["id_racun"], "username" => $row["username"], "naslov" => $row["naslov"], "datum" => $row["datum"],
                "termin" => $row["termin"], "dvorana" => $row["dvorana"],"red" =>$row["red"], "sjedalo" => $row["sjedalo"], "cijena" => $row["cijena"]);
            }
            return $ulaznice;
        }

        function dohvatiRezervaciju($id)
        {
          $db = DB::getConnection();
          $st = $db->prepare("SELECT * FROM projekt_rezervacije WHERE id = :id");
          $st->execute(["id" => $id]);

          $row = $st->fetch();
          $rezervacija = array("id"=> $row["id"], "username"=>$row["username"], "naslov" => $row["naslov"], "datum" => $row["datum"], "termin" => $row["termin"],
                              "dvorana" =>$row["dvorana"], "broj_ulaznica" =>  $row["broj_ulaznica"], "sjedala" => $row["sjedala"], "cijena" => $row["cijena"]);

          return $rezervacija;
        }

        function osvjeziRezervacije()
        {
            date_default_timezone_set('Europe/Zagreb');
            $datum = date('Y-m-d');
            $vrijeme=date('H:i:s');

            $db = DB::getConnection();

            $st = $db->prepare("DELETE FROM projekt_rezervacije WHERE datum < :datum OR (datum = :datum AND termin < :termin)");
            $st->execute(["datum" => $datum, 'termin' => $vrijeme]);

        }

        function promijeniRezervaciju($id, $naslov, $datum, $termin, $dvorana, $broj_ulaznica, $sjedala, $cijena)
        {
            $db = DB::getConnection();

            $st = $db->prepare('UPDATE projekt_rezervacije SET naslov = :naslov, datum = :datum, termin = :termin, dvorana = :dvorana,
                                broj_ulaznica = :broj_ulaznica, sjedala = :sjedala, cijena = :cijena WHERE id = :id');
            $st->execute(array('naslov'=>$naslov, 'datum'=>$datum, 'termin'=>$termin, 'dvorana'=>$dvorana,
                               'broj_ulaznica'=>$broj_ulaznica, 'sjedala'=>$sjedala, 'cijena'=>$cijena, 'id'=>$id));

            return $id;
        }

        function filmoviRaspored()
        {
            $db = DB::getConnection();
            $st = $db->prepare("SELECT DISTINCT ime FROM projekt_filmovi INNER JOIN projekt_raspored ON projekt_filmovi.id=projekt_raspored.id_film");
            $st->execute();

            $filmovi = [];
            while($row = $st->fetch())
            {
                $filmovi[] = $row["ime"];
            }
            return $filmovi;
        }

        function datumiFilma($ime)
        {
            // dohvati sve datume prikazivanja za dani film
            // samo datumi od danas nadalje

            date_default_timezone_set('Europe/Zagreb');
            $datum = date('Y-m-d');
            $vrijeme=date('H:i:s');

            $db = DB::getConnection();
            $st = $db->prepare('SELECT datum, termin FROM projekt_raspored INNER JOIN projekt_filmovi ON projekt_filmovi.id=projekt_raspored.id_film
                                AND ime = :ime AND datum >= :datum');
            $st->execute(["ime" => $ime, "datum" => $datum]);

            $datumi = [];
            while($row = $st->fetch())
            {
              if($row["datum"] == $datum && $row["termin"] < $vrijeme) continue;
              if(!in_array($row["datum"], $datumi)) $datumi[] = $row["datum"];
            }
            return $datumi;
        }

        function terminiFilma($ime, $datum)
        {
            // dohvati sve termine prikazivanja za dani film i dani datum

            $db = DB::getConnection();
            $st = $db->prepare('SELECT DISTINCT termin FROM projekt_raspored INNER JOIN projekt_filmovi ON projekt_filmovi.id=projekt_raspored.id_film
                                AND ime = :ime AND datum = :datum');
            $st->execute(["ime" => $ime, "datum" => $datum]);

            $termini = [];
            while($row = $st->fetch())
            {
              $termini[] = $row["termin"];
            }
            return $termini;
        }

        function dvoraneFilma($ime, $datum, $termin)
        {
            // dohvati sve dvorane za dani film, dani datum i dani termin

            $db = DB::getConnection();
            $st = $db->prepare('SELECT dvorana FROM projekt_raspored INNER JOIN projekt_filmovi ON projekt_filmovi.id=projekt_raspored.id_film
                                AND ime = :ime AND datum = :datum AND termin = :termin');
            $st->execute(["ime" => $ime, "datum" => $datum, "termin" => $termin]);

            $dvorane = [];
            while($row = $st->fetch())
            {
              $dvorane[] = $row["dvorana"];
            }
            return $dvorane;
        }

}
?>
