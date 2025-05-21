import $, { timers } from 'jquery';
import { gsap } from 'gsap';
import { Draggable } from "gsap/Draggable";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import { ScrollTrigger } from "gsap/ScrollTrigger";

import menu from "../images/menu/menu.png";
import croix from "../images/menu/fermer.webp";

gsap.registerPlugin(Draggable, ScrollToPlugin, ScrollTrigger);

window.MyApp = {
    navMenu,
    competences,
    accueil,
    experiences,
    contact,
    afficherCompetencesJQuery,
    toggleDivs,

};

window.addEventListener('load', () => {
    navMenu(document.title);
    setupMenuToggle();
    setupSectionClick();
    setupToggleDivs();

});

document.addEventListener('DOMContentLoaded', setupAutocomplete);

function setupMenuToggle() {
    $(".imgMenu").on("click", () => {
        const isMenuOpen = $(".navDiva").hasClass("menu-ouvert");
        $(".navDiva").toggleClass("menu-ouvert", !isMenuOpen);

        const newSrc = isMenuOpen ? menu : croix;
        const rotation = isMenuOpen ? 0 : 360;

        gsap.to(".imgMenu", {
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                $(".imgMenu").attr("src", newSrc);
                gsap.to(".imgMenu", {
                    opacity: 1,
                    rotation,
                    duration: 0.5,
                    ease: "power2.out"
                });
            }
        });
    });
}

function setupSectionClick() {
    $("section").on("click", () => {
        if ($(".navDiva").hasClass("menu-ouvert")) {
            $(".navDiva").removeClass("menu-ouvert");
            $(".imgMenu").attr("src", menu);
        }
    });
}

function setupAutocomplete() {
    const searchInput = document.getElementById('search');
    const suggestionsList = document.getElementById('suggestions');

    if (!searchInput || !suggestionsList) return;

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim(); // Récupère la valeur saisie, sans espaces inutiles

        // Efface les suggestions si la requête est trop courte
        if (query.length < 2) {
            suggestionsList.innerHTML = '';
            return;
        }

        // Récupère les suggestions d'autocomplétion depuis le serveur
        fetch(`/fr/autocomplete?q=${encodeURIComponent(query)}`)
            .then(response => response.json()) // Analyse la réponse JSON
            .then(data => renderSuggestions(data, suggestionsList)) // Affiche les suggestions
            .catch(error => console.error('Erreur:', error)); // Affiche les erreurs dans la console
    });
}


function openPopup(element) {
    const userId = element.dataset.userId;
    const showcv = document.getElementById('showcv');


    // Appelle la route Symfony via AJAX (fetch)
    fetch(`/fr/showcv/${userId}`)
        .then(response => response.json())
        .then(data => {
            showcv.style.display = 'block';

            document.getElementById('cv-nom-prenom').textContent = `${data.prenom} ${data.nom}`;
            document.getElementById('cv-profession').textContent = data.profession;
            document.getElementById('cv-email').textContent = data.email;
            document.getElementById('cv-telephone').textContent = data.telephone;
            document.getElementById('cv-description').textContent = data.description;
            // Compétences
            document.getElementById('cv-competences').innerHTML = data.competences.map(c => `<div>${c}</div>`).join('');
            // Outils
            document.getElementById('cv-outils').innerHTML = data.outils.map(o => `<div>${o}</div>`).join('');
            // Langues
            document.getElementById('cv-langues').innerHTML = data.langues.map(l => `<div>${l.nom} (${l.niveau})</div>`).join('');
            // Loisirs
            document.getElementById('cv-loisirs').innerHTML = data.loisirs.map(l => `<div>${l}</div>`).join('');
            // Expériences professionnelles
            document.getElementById('cv-exp-pro').innerHTML = data.experiencesPro.map(exp => `
                <div>
                    <strong>${exp.poste}</strong> chez ${exp.entreprise}<br>
                    ${exp.dateDebut} – ${exp.dateFin}<br>
                </div>
            `).join('');
            // Expériences universitaires
            document.getElementById('cv-exp-uni').innerHTML = data.experiencesUni.map(exp => `
                <div>
                    <strong>${exp.titre}</strong> ${exp.sousTitre}<br>
                    ${exp.annee} <br>
                </div>
            `).join('');
            // Formations
            document.getElementById('cv-formations').innerHTML = data.formations.map(f => `
                <div>
                    <strong>${f.intitule}</strong> à ${f.lieu}<br>
                    ${f.annee}<br>
                </div>
            `).join('');
        })
        .catch (error => console.error('Erreur:', error));

}

function closePopup() {
    document.getElementById('showcv').style.display = 'none';
}





function renderSuggestions(data, suggestionsList) {
    suggestionsList.innerHTML = '';
    data.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item.value;
        li.addEventListener('click', () => navigateToItem(item));
        suggestionsList.appendChild(li);
    });
}

function navigateToItem(item) {
    const locale = document.documentElement.lang || 'fr';
    const basePath = `/${locale}/${item.slug}`;
    const paths = {
        "experience_pro": `${basePath}/experiences`,
        "experience_uni": `${basePath}/experiences`,
        "competence": `${basePath}/competences`,
        "outil": `${basePath}/competences`,
        "langue": `${basePath}/competences`
    };
    window.location.href = paths[item.field] || basePath;
}

function setupToggleDivs() {
    const option1 = document.getElementById('option1');
    const option2 = document.getElementById('option2');
    if (option1 && option2) {
        option1.onclick = toggleDivs;
        option2.onclick = toggleDivs;
    }
}

function toggleDivs() {
    const divOption1 = document.getElementById('divOption1');
    const divOption2 = document.getElementById('divOption2');
    const option1Checked = document.getElementById('option1').checked;

    if (divOption1 && divOption2) {
        divOption1.style.display = option1Checked ? 'block' : 'none';
        divOption2.style.display = option1Checked ? 'none' : 'block';
    }
}

function navMenu(title) {
    const actions = {
        "CV - Compétences": competences,
        "CV - Skills": competences,
        "CV - Competencias": competences,
        "CV - Profil": accueil,
        "CV - Profile": accueil,
        "CV - Perfil": accueil,
        "CV - Expériences": experiences,
        "CV - Experiences": experiences,
        "CV - Experiencias": experiences,
        "CV - Contact": contact,
        "CV - Contacto": contact
    };

    const action = actions[title];
    if (action) action();
}

function competences() {
    updateNavUnderline("#navCompetences");
    setPourcentage();
    afficherCompetencesJQuery();
    animateProgressBars();
    animateSections(".css-sectionComp2", ".css-sectionComp3");
}

function accueil() {
    updateNavUnderline("#navAccueil");
    animateAccueilElements();
    setupScrollToAPropos();
    animateLoisirs();
}

function experiences() {
    updateNavUnderline("#navExperiences");
    animateExperienceSections(".sectionExperiencePro", ".expPro");
    animateExperienceSections(".sectionExperienceUni", ".expUni");
}

function contact() {
    updateNavUnderline("#navContact");
}

function updateNavUnderline(selector) {
    $(".underline").removeClass("underline");
    $(selector).addClass("underline");
}

function animateProgressBars() {
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const finalValue = parseInt(bar.getAttribute('data-pourcent'), 10);
        let currentValue = 0;

        const interval = setInterval(() => {
            currentValue++;
            bar.style.background = `
                radial-gradient(closest-side, white 79%, transparent 80% 100%),
                conic-gradient(rgb(249, 154, 77) ${currentValue}%, rgb(255, 243, 203) 0)
            `;
            bar.setAttribute('data-pourcent', currentValue);

            if (currentValue >= finalValue) clearInterval(interval);
        }, 30);
    });
}

function animateSections(...selectors) {
    selectors.forEach(selector => {
        gsap.set($(selector), { opacity: 0, height: 0 });
        gsap.to(selector, {
            duration: 1,
            ease: "power2.out",
            opacity: 1,
            height: "100%",
            scrollTrigger: {
                trigger: selector,
                toggleActions: "play none none none",
                start: "top center"
            }
        });
    });
}

function animateAccueilElements() {
    gsap.set("#imgmoi", { x: -200 });
    gsap.to("#imgmoi", { duration: 1, x: 0 });

    gsap.set(".divInfo", { x: 200 });
    gsap.to(".divInfo", { duration: 1, x: 0 });

    gsap.set(".imgParcours", { y: 600 });
    gsap.to(".imgParcours", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: "#a-propos",
            toggleActions: "play pause none none",
            start: "top center"
        }
    });

    gsap.set("#a-propos-div", { y: 400 });
    gsap.to("#a-propos-div", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: "#a-propos",
            toggleActions: "play none none none",
            start: "top center"
        }
    });
}

function setupScrollToAPropos() {
    $("#aBoutton").on("click", () => {
        gsap.to(window, {
            duration: 1,
            scrollTo: { y: "#a-propos", offsetY: 100 }
        });
    });
}

function animateLoisirs() {
    Draggable.create(".imgLoisirs", { type: "x", bounds: ".sectionIndex3" });

    gsap.set(".imgLoisirs:nth-child(1)", { x: -50 });
    gsap.set(".imgLoisirs:nth-child(2)", { x: -450 });
    gsap.set(".imgLoisirs:nth-child(3)", { x: -650 });

    gsap.to(".imgLoisirs", {
        x: 0,
        duration: 2,
        scrollTrigger: {
            trigger: ".sectionIndex3",
            toggleActions: "play none none none",
            start: "top center"
        }
    });

    gsap.set(".divLoisirs", { y: 100 });
    gsap.to(".divLoisirs", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: ".sectionIndex3",
            toggleActions: "play none none none",
            start: "top center"
        }
    });
}

function animateExperienceSections(sectionSelector, itemSelector) {
    const items = gsap.utils.toArray(itemSelector);
    const coef = window.innerWidth < 768 ? 400 : 850;
    const endValue = `+=${coef * items.length / 1.5}`;

    gsap.to(items, {
        xPercent: -100 * 1.2 * (items.length - 1),
        ease: "none",
        scrollTrigger: {
            trigger: sectionSelector,
            pin: true,
            scrub: 1,
            snap: 1 / (items.length - 1),
            end: endValue,
            start: "top top"
        }
    });
}

function afficherCompetencesJQuery() {
    $(".js-langage").each(function (index) {
        setTimeout(() => {
            const $nomLangage = $(this).find(".js-nomLangage");
            const $progressBar = $(this).find(".js-progressBar");
            const $pourcentageElem = $(this).find(".progress-bar");

            [$nomLangage, $progressBar, $pourcentageElem].forEach($el => $el.css("visibility", "visible"));

            const finalValue = parseInt($pourcentageElem.attr('data-pourcent'), 10);
            let currentValue = 0;

            $pourcentageElem.attr("data-pourcent", "0");

            const interval = setInterval(() => {
                currentValue++;
                $pourcentageElem.attr("data-pourcent", currentValue);

                if (currentValue >= finalValue) clearInterval(interval);
            }, 20);

            gsap.set($(this), { opacity: 0 });
            gsap.to($(this), { opacity: 1, duration: 1 });
        }, 200 * index);
    });
}

function setPourcentage() {
    document.querySelectorAll('.langages__progress--bar').forEach(bar => {
        bar.style.height = `${bar.getAttribute('data-pourcent')}%`;
    });
}
window.openPopup = openPopup;
window.closePopup = closePopup;