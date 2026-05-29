# Webová aplikace Školní kroužky (Ročníkový projekt)

Tento repozitář obsahuje zdrojové kódy pro webovou aplikaci správy školních kroužků. Projekt vznikl jako ročníková práce do předmětu Programování. Cílem aplikace je digitalizovat proces přihlašování žáků do zájmových kroužků a usnadnit jejich správu učitelům a administrátorům.

## Živá ukázka 

Aplikaci si můžete reálně vyzkoušet zde: [Web](https://devstudio.free.nf/)

---

## Hlavní funkce

Systém je rozdělen pro 3 typy uživatelů, z nichž každý má specifické rozhraní a práva:

### Žák
- Přihlášení do systému pomocí uživatelského jména a hesla.
- Prohlížení nabídky všech dostupných aktivních kroužků.
- Přihlášení a odhlášení z libovolného kroužku (s kontrolou kapacity).
- Přehled vlastních kroužků a osobní rozvrh.
<p>
  <img width="1278" height="676"" alt="Image" src="https://github.com/user-attachments/assets/817781ec-e510-438d-b42f-9f2154613371" />
  &nbsp;
  <img width="1278" height="676" alt="Image" src="https://github.com/user-attachments/assets/b12eba12-b333-4692-b49b-a24957ff51ba" />
  &nbsp;
  <img width="1278" height="676" alt="Image" src="https://github.com/user-attachments/assets/2c354b4c-2034-46b6-8094-f15c0c52d6d4" />
</p>

### Učitel
- Přehled kroužků, které daný učitel vede.
- Zobrazení seznamu účastníků pro každý svůj kroužek.
- Přehled obsazenosti (kapacita / přihlášení žáci).
- Rozvrh zobrazující vlastní kroužky v tabulkovém formátu vyučovacích hodin.

<p>
  <img width="1278" height="676"  alt="obrazek" src="https://github.com/user-attachments/assets/86d6010f-36cb-424f-804a-aa8e68f5d62b" />
  &nbsp;
  <img width="1278" height="676" alt="obrazek" src="https://github.com/user-attachments/assets/9204312c-fd6d-4de4-8234-b019a5517e69" />
  &nbsp;
  <img width="1278" height="676" alt="obrazek" src="https://github.com/user-attachments/assets/ef81fdc0-c86f-4eb8-a577-ccf8be3450c3" />
</p>

### Administrátor 
- **Správa kroužků:** Přidávání nových kroužků, editace stávajících (název, popis, učitel, kapacita, čas, místnost) a jejich deaktivace.
- **Správa uživatelů:** Přidávání nových uživatelů všech rolí (žák, učitel, admin) a jejich mazání.
- **Přehled přihlášení:** Kompletní tabulka všech přihlášení žáků do kroužků s detaily.
- **Dashboard:** Souhrnný přehled všech kroužků s jejich obsazeností.
<p>
  <img width="1278" height="676" alt="obrazek" src="https://github.com/user-attachments/assets/15bb6d88-8cd1-465c-a1d3-271e05f998f8" />
  &nbsp;
  <img width="1278" height="676" alt="obrazek" src="https://github.com/user-attachments/assets/fd6a72d9-dd4b-4eb9-b509-2a4943258bfc" />
  &nbsp;
  <img width="1278" height="676" alt="obrazek" src="https://github.com/user-attachments/assets/585ab4a2-8cf4-4d2c-a23a-cfc58109360d" />
</p>

---

## Použité technologie 

- **Frontend:** HTML, CSS (čisté CSS bez frameworku), JavaScript
- **Backend:** PHP
- **Databáze:** MySQL 

---

## Struktura projektu 

Projekt využívá oddělené složky pro každou roli:

```text
main/
├── index.php                  # Přihlašovací stránka
├── odhlasit.php               # Odhlášení a zničení session
├── config.php                 # Připojení k databázi, pomocné funkce
│
├── admin/
│   ├── dashboard.php          # Přehled všech kroužků (admin)
│   ├── krouzky.php            # Správa kroužků – přidání, editace, smazání
│   ├── uzivatele.php          # Správa uživatelů – přidání, smazání
│   ├── prihlaseni.php         # Přehled všech přihlášení žáků
│   ├── hlavicka.php           # Sdílená hlavička a navigace (admin)
│   └── paticka.php            # Sdílená patička (admin)
│
├── ucitel/
│   ├── prehled.php            # Přehled kroužků učitele
│   ├── moje_krouzky.php       # Detailní karty vlastních kroužků
│   ├── ucastnici.php          # Seznam žáků ve vlastních kroužcích
│   ├── rozvrh.php             # Tabulkový rozvrh kroužků
│   ├── hlavicka.php           # Sdílená hlavička a navigace (učitel)
│   └── paticka.php            # Sdílená patička (učitel)
│
├── zak/
│   ├── krouzky.php            # Nabídka kroužků + přihlášení / odhlášení
│   ├── moje_krouzky.php       # Přehled kroužků žáka
│   ├── rozvrh.php             # Osobní rozvrh přihlášených kroužků
│   ├── hlavicka.php           # Sdílená hlavička a navigace (žák)
│   └── paticka.php            # Sdílená patička (žák)
│
├── css/
│   ├── style.css              # Hlavní styly aplikace
│   └── login.css              # Styly přihlašovací stránky
│
├── js/
│   └── main.js                # Klientské scripty (animace, flash zprávy)
│
└── if0_41951673_db_school.sql # SQL dump databáze
```

---

## Databázová struktura

Aplikace využívá 3 tabulky:

| Tabulka | Popis |
|---|---|
| `uzivatele` | Všichni uživatelé systému (žáci, učitelé, admini) |
| `krouzky` | Záznamy o kroužcích (název, čas, místnost, kapacita, vedoucí) |
| `prihlaseni` | Vazební tabulka – přihlášení konkrétního žáka do konkrétního kroužku |

---

### ER diagram
<img width="798" height="1110" alt="obrazek" src="https://github.com/user-attachments/assets/74209fd4-a816-488c-8c51-933e9bfc8697" />


## Testovací účty

Všechny testovací účty sdílejí stejné heslo.

| Role | Uživatelské jméno | Heslo |
|---|---|---|
| Žák | `zak1` | `heslo123` |
| Učitel | `ucitel1` | `heslo123` |
| Administrátor | `admin` | `heslo123` |

---

## Instalace a spuštění

1. Naklonuj repozitář nebo nahraj soubory na webový server s podporou PHP a MySQL.
2. Vytvoř databázi s názvem `if0_41951673_db_school` a importuj databázi ze souboru `if0_41951673_db_school.sql` do svého MySQL serveru.
3. Upravte přihlašovací údaje k databázi v souboru `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'if0_41951673_db_school');
   define('DB_USER', 'uzivatel');
   define('DB_PASS', 'heslo');
   ```
4. Otevři aplikaci v prohlížeči a přihlaš se jedním z testovacích účtů.

---

## Závěr

Podařilo se vytvořit funkční webovou aplikaci pro správu školních kroužků s podporou více uživatelských rolí. Projekt zahrnuje práci s databází, autentizaci uživatelů, správu dat i responzivní uživatelské rozhraní.
Během vývoje byly využity technologie HTML, CSS, JavaScript, PHP a MySQL. Projekt zároveň pomohl prohloubit znalosti práce s databázemi, backendovou logikou i návrhem webových aplikací. Při vývoji projektu byly využity AI nástroje pouze pro pomoc s vytvářením rozvrhů a opravy některých chyb v kódu.
