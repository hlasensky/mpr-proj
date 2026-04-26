Fakulta informačních technologií  
Ústav informačních systémů

## Management projektů

## Systém pro podporu řízení rizik v projektech

## NÁVRH SYSTÉMU

## 404 TNNF

**Autor:** Tomáš Hlásenský (xhlase01)

---

## Historie verzí

| Verze | Datum      | Status | Kdo      | Poznámka                                 |
| ----- | ---------- | ------ | -------- | ---------------------------------------- |
| 1.0   | 2026-04-08 | Draft  | xhlase01 | Vytvoření dokumentu a základní struktura |

---

## Obsah

1. [Úvod](#1-úvod)
2. [Dynamická struktura](#2-dynamická-struktura)
    - 2.1 [Sekvenční diagramy](#21-sekvenční-diagramy)
    - 2.2 [Diagramy spolupráce](#22-diagramy-spolupráce)
3. [Statická struktura](#3-statická-struktura)
    - 3.1 [Package diagram](#31-package-diagram)
    - 3.2 [Diagram tříd](#32-diagram-tříd)
4. [Návrh GUI](#4-návrh-gui)
5. [Návrh databáze](#5-návrh-databáze)
6. [Specifikace uživatelských testů](#6-specifikace-uživatelských-testů)

---

## 1. Úvod

Tento dokument představuje návrh systému pro podporu řízení rizik v projektech. Systém je určen projektovým manažerům a jejich nadřízeným. Umožňuje evidenci projektů, identifikaci a hodnocení rizik, vizualizaci rizik pomocí matice a správu uživatelských účtů.

Systém je implementován jako webová aplikace postavenou na frameworku **Laravel 12** s **Livewire v3** pro reaktivní uživatelské rozhraní a **SQLite** jako datovým úložištěm.

Jsou definovány dvě uživatelské role:

- **Super manager** – plný přístup ke všem datům a správě uživatelů.
- **Project manager** – přístup pouze ke svým projektům a rizikům.

---

## 2. Dynamická struktura

Specifikace dynamické struktury vytvářeného systému pomocí sekvenčních diagramů a diagramů spolupráce pro klíčové use-case scénáře.

### 2.1 Sekvenční diagramy

#### 2.1.1 Registrace uživatele (F-004)

Popis scénáře: Nový uživatel se registruje do systému zadáním jména, e-mailu a hesla. Systém ověří unikátnost e-mailu, zahashuje heslo a vytvoří uživatele ve stavu pro ověření.

## ![SD-01-Registrace](../out/xhlase01/diagrams/SD-01-Registrace/SD-01-Registrace.png)


#### 2.1.2 Ověření uživatele – změna role z unverified na project manager (F-005)

Popis scénáře: Po registraci má nový uživatel roli `unverified` a nemá přístup k funkcím systému. Super manager vidí seznam neověřených uživatelů a může každého ručně ověřit, čímž mu přidělí roli `project_manager`.

![SD-07-OvereniUzivatele](../out/xhlase01/diagrams/SD-07-OvereniUzivatele/SD-07-OvereniUzivatele.png)

---

#### 2.1.3 Přihlášení uživatele (F-004)

Popis scénáře: Existující uživatel se přihlásí pomocí e-mailu a hesla. Systém ověří přihlašovací údaje a vytvoří session.

![SD-02-Prihlaseni](../out/xhlase01/diagrams/SD-02-Prihlaseni/SD-02-Prihlaseni.png)

---

#### 2.1.4 Vytvoření projektu (F-001)

Popis scénáře: Přihlášený uživatel vytvoří nový projekt zadáním názvu, popisu a termínu. Systém ověří unikátnost názvu pro daného uživatele.

![SD-03-VytvoreniProjektu](../out/xhlase01/diagrams/SD-03-VytvoreniProjektu/SD-03-VytvoreniProjektu.png)

---

#### 2.1.5 Přidání rizika k projektu (F-002)

Popis scénáře: Manager otevře detail projektu a přidá nové riziko se jménem, dopadem a pravděpodobností.

![SD-04-PridaniRizika](../out/xhlase01/diagrams/SD-04-PridaniRizika/SD-04-PridaniRizika.png)

---

#### 2.1.6 Zobrazení matice rizik (F-003)

Popis scénáře: Manager přepne zobrazení projektu z tabulkového na maticové. Systém načte všechna rizika projektu a vykreslí heat-mapu.

![SD-05-MaticeRizik](../out/xhlase01/diagrams/SD-05-MaticeRizik/SD-05-MaticeRizik.png)

---

#### 2.1.7 Správa manažerů – změna role a smazání (F-005)

Popis scénáře: Super manager zobrazí seznam všech uživatelů. Může změnit roli libovolného uživatele nebo ho smazat ze systému. Vytváření uživatelů probíhá pouze registrací.

![SD-06-SpravaManazerů](../out/xhlase01/diagrams/SD-06-SpravaManazerů/SD-06-SpravaManazerů.png)

---


### 2.2 Diagramy spolupráce

Diagramy spolupráce jsou alternativní reprezentací sekvenčních diagramů se zaměřením na vztahy mezi objekty namísto časové osy.

#### 2.2.1 Diagram spolupráce – Registrace a přihlášení (F-004)

![CD-01-Auth](../out/xhlase01/diagrams/CD-01-Auth/CD-01-Auth.png)

---

#### 2.2.2 Diagram spolupráce – Správa projektů a rizik (F-001, F-002)

![CD-02-ProjektyRizika](../out/xhlase01/diagrams/CD-02-ProjektyRizika/CD-02-ProjektyRizika.png)

---

#### 2.2.3 Diagram spolupráce – Vizualizace matice rizik (F-003)

![CD-03-MaticeRizik](../out/xhlase01/diagrams/CD-03-MaticeRizik/CD-03-MaticeRizik.png)

---

## 3. Statická struktura

Specifikace statické struktury vytvářeného systému. Zahrnuje package diagram pro vysokou úroveň abstrakce a detailní diagram tříd.

### 3.1 Package diagram

Systém je rozdělen do tří hlavních vrstev odpovídajících architektuře Laravel MVC.

![PD-01-Package](../out/xhlase01/diagrams/PD-01-Package/PD-01-Package.png)

---

### 3.2 Diagram tříd

Detailní statická struktura tříd systému včetně atributů, metod a vazeb.

![CD-Tridy](../out/xhlase01/diagrams/CD-Tridy/CD-Tridy.png)

**Popis tříd:**

- **Manager** – reprezentuje uživatele systému. Atribut `role` určuje přístupová práva. Metoda `isAdmin()` vrací `true` pokud je role `Super manager`.
- **Project** – kontejner pro rizika vlastněný jedním manažerem. Metoda `getRiskScore()` vrací průměrné skóre rizik projektu.
- **Risk** – konkrétní riziko přiřazené k projektu. Vypočítaný atribut `score = probability × impact`. Metoda `getSeverityAttribute()` vrací textový label (Nízké/Střední/Vysoké/Kritické) podle hodnoty skóre.

---

## 4. Návrh GUI

Předběžný návrh uživatelských obrazovek. Systém používá responzivní webové rozhraní s postranním panelem pro navigaci.

![GUI-Navigace](../out/xhlase01/diagrams/GUI-Navigace/GUI-Navigace.png)

**Popis obrazovek:**

| Obrazovka             | Popis                                                                                      |
| --------------------- | ------------------------------------------------------------------------------------------ |
| **Login / Register**  | Vstupní obrazovka s formulářem. Přesměrování na dashboard po úspěšné autentizaci.          |
| **Dashboard**         | Kartičky projektů s názvem, termínem a počtem otevřených rizik.                            |
| **Detail projektu**   | Tabulka rizik s filtry (stav, závažnost). Tlačítko "Přidat riziko". Záložky pro přepínání. |
| **Matice rizik**      | Heat-mapa 5×5. Osa X = pravděpodobnost, osa Y = dopad. Body = jednotlivá rizika.           |
| **Správa manažerů**   | Tabulka uživatelů (pouze Admin). CRUD operace nad manažery.                                |
| **Nastavení profilu** | Editace vlastního jména, e-mailu a hesla.                                                  |

---

## 5. Návrh databáze

Datové úložiště je realizováno pomocí **SQLite**. Struktura odpovídá diagramu tříd.

![DB-Schema](../out/xhlase01/diagrams/DB-Schema/DB-Schema.png)

**Poznámky k databázi:**

- Hesla jsou uložena jako **bcrypt hash** (nikdy plain-text).
- Pole označená `*` jsou povinná (NOT NULL).
- Mazání projektu kaskádně smaže všechna přiřazená rizika (`ON DELETE CASCADE`).
- Mazání manažera kaskádně smaže jeho projekty a rizika.

---

## 6. Specifikace uživatelských testů

V průběhu návrhu systému jsou specifikovány uživatelské testy ověřující funkcionalitu klíčových scénářů.

### 6.1 Registrace a přihlášení

| Atribut                | Hodnota                                                                                                                                                                       |
| ---------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                 | UT-01                                                                                                                                                                         |
| **Název**              | Registrace nového uživatele a přihlášení                                                                                                                                      |
| **Předpoklady**        | Systém je dostupný, e-mail nebyl dosud registrován.                                                                                                                           |
| **Kroky**              | 1. Otevřít stránku `/register`. 2. Vyplnit jméno, e-mail, heslo. 3. Kliknout "Registrovat". 4. Ověřit přesměrování na dashboard. 5. Odhlásit se. 6. Přihlásit se na `/login`. |
| **Očekávaný výsledek** | Po registraci i přihlášení je uživatel přesměrován na dashboard. Zobrazí se seznam projektů.                                                                                  |
| **Kritérium úspěchu**  | Obě akce proběhnou bez chyby do 500 ms.                                                                                                                                       |

---

### 6.2 Vytvoření projektu

| Atribut                | Hodnota                                                                                    |
| ---------------------- | ------------------------------------------------------------------------------------------ |
| **ID**                 | UT-02                                                                                      |
| **Název**              | Vytvoření nového projektu                                                                  |
| **Předpoklady**        | Uživatel je přihlášen v systému.                                                           |
| **Kroky**              | 1. Na dashboardu kliknout "Nový projekt". 2. Vyplnit název a termín. 3. Kliknout "Uložit". |
| **Očekávaný výsledek** | Projekt se zobrazí na dashboardu i v detailu. Název projektu odpovídá zadanému.            |
| **Kritérium úspěchu**  | Projekt je viditelný ihned po uložení bez nutnosti obnovit stránku.                        |

---

### 6.3 Přidání rizika a zobrazení matice

| Atribut                | Hodnota                                                                                                                                                                               |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                 | UT-03                                                                                                                                                                                 |
| **Název**              | Přidání rizika a zobrazení v matici                                                                                                                                                   |
| **Předpoklady**        | Existuje alespoň jeden projekt.                                                                                                                                                       |
| **Kroky**              | 1. Otevřít detail projektu. 2. Kliknout "Přidat riziko". 3. Zadat název "Výpadek serveru", dopad: 8, pravděpodobnost: 3. 4. Kliknout "Uložit". 5. Přepnout na záložku "Matice rizik". |
| **Očekávaný výsledek** | Riziko se zobrazí v tabulce i jako bod v matici na pozici [3, 8].                                                                                                                     |
| **Kritérium úspěchu**  | Matice je vykreslena do 300 ms. Bod rizika je ve správném kvadrantu.                                                                                                                  |

---

### 6.4 Správa manažerů (Admin)

| Atribut                | Hodnota                                                                                                                         |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                 | UT-04                                                                                                                           |
| **Název**              | Ověření nového manažera jako Admin                                                                                              |
| **Předpoklady**        | Přihlášený uživatel má roli `super-manager`.                                                                                    |
| **Kroky**              | 1. Přejít na "Správa manažerů" v sidebaru. 2. Vyhledá manažery s rolí `unverified`. 3. Kliknout "Ověřit".                   |
| **Očekávaný výsledek** | Manažerovi se změní role na `project-manager`.                                                                            |
| **Kritérium úspěchu**  | Manažer může ihned přistoupit k systému. Pokus neSuper managera o přístup na tuto stránku vrátí 403.                            |

---

### 6.5 Validace vstupů

| Atribut                | Hodnota                                                                                                                                       |
| ---------------------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| **ID**                 | UT-05                                                                                                                                         |
| **Název**              | Ověření validace formulářů                                                                                                                    |
| **Předpoklady**        | Uživatel je přihlášen.                                                                                                                        |
| **Kroky**              | 1. Pokusit se přidat riziko s dopadem 6. 2. Pokusit se vytvořit projekt s prázdným názvem. 3. Pokusit se registrovat s existujícím e-mailem. |
| **Očekávaný výsledek** | Ve všech případech systém zobrazí příslušnou chybovou hlášku. Data nejsou uložena do databáze.                                                |
| **Kritérium úspěchu**  | Chybová hláška se zobrazí do 200 ms bez odeslání formuláře na server (Livewire real-time validace).                                           |
