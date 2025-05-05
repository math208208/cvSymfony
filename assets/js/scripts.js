import $, { timers } from 'jquery';
import { gsap } from 'gsap';
import { Draggable } from "gsap/Draggable";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import { ScrollTrigger } from "gsap/ScrollTrigger";


import menu from "../images/menu/menu.png";
import croix from "../images/menu/fermer.webp";

gsap.registerPlugin(Draggable);
gsap.registerPlugin(ScrollToPlugin);
gsap.registerPlugin(ScrollTrigger);



window.MyApp = {
    navMenu,
    competences,
    accueil,
    experiences,
    contact,
    afficherCompetencesJQuery
};


window.addEventListener('load', () => {
    const titleDoc = document.title;
    navMenu(titleDoc);

    $(".imgMenu").on("click", function () {
        if ($(".navDiva").hasClass("menu-ouvert")) {
            $(".navDiva").removeClass("menu-ouvert");

            gsap.to(".imgMenu", {
                opacity: 0,
                duration: 0.2,

                onComplete: function () {
                    $(".imgMenu").attr("src", menu);
                    gsap.to(".imgMenu",
                        { opacity: 1, rotation: 0, duration: 0.5, ease: "power2.out" },
                    );
                }
            });
        } else {
            $(".navDiva").addClass("menu-ouvert");

            gsap.to(".imgMenu", {
                opacity: 0,
                duration: 0.2,

                onComplete: function () {
                    $(".imgMenu").attr("src", croix);
                    gsap.to(".imgMenu",
                        { opacity: 1, rotation: 360, duration: 0.5, ease: "power2.out" },
                    );
                }
            });
        }
    });

    $("section").on("click", function () {
        if ($(".navDiva").hasClass("menu-ouvert")) {
            $(".navDiva").removeClass("menu-ouvert");
            $(".imgMenu").attr("src", menu);
        }
    })
});

function navMenu(titreDoc) {
    switch (titreDoc) {
        case "CV - Compétences" :
        case "CV - Skills":
        case "CV - Competencias":
            competences();
            break;
        case "CV - Accueil" :
        case "CV - Home":
        case "CV - Inicio":
            accueil();
            break;
        case "CV - Expériences" :
        case "CV - Experiences":
        case "CV - Experiencias":
            experiences();
            break;
        case "CV - Contact":
        case "CV - Contacto":
            contact();
            break;
        default:
            break;
    }
}



function competences() {
    $("#navCompetences").addClass("underline");
    $("#navAccueil").removeClass("underline");
    setPourcentage();
    afficherCompetencesJQuery();

    var widthIcon = $(".outilImage").width();
    gsap.set($(".outilImage"), { width: 0, opacity: 0 });

    gsap.to(".outilImage", {
        duration: 1,
        ease: "power2.out",
        rotation: 360,
        width: widthIcon,
        opacity: 1,
        scrollTrigger: {
            trigger: ".css-sectionComp2",
            toggleActions: "play none none none",
            start: "top center"
        }
    });




    gsap.set($(".css-sectionComp2"), { opacity: 0, height: 0 });
    gsap.to(".css-sectionComp2", {
        duration: 1,
        ease: "power2.out",
        opacity: 1,
        height: "100%",
        scrollTrigger: {
            trigger: ".css-sectionComp2",
            toggleActions: "play none none none",
            start: "top center",

        }
    });




    gsap.set($(".css-sectionComp3"), { opacity: 0 });
    gsap.to(".css-sectionComp3", {
        duration: 1,
        opacity: 1,
        scrollTrigger: {
            trigger: ".css-sectionComp3",
            toggleActions: "play none none none",
            start: "top center"
        }
    });

}





function accueil() {
    $("#navAccueil").addClass("underline");

    //rotation photo moi
    gsap.set("#imgmoi", { x: -200 });
    gsap.to("#imgmoi", {
        duration: 1,
        x: 0
    })
    //rotation info moi
    gsap.set(".divInfo", { x: 200 });
    gsap.to(".divInfo", {
        x: 0,
        duration: 1,
    });

    //descend jusqua a propos
    const $bunttonApropos = $("#aBoutton");

    $bunttonApropos.on("click", function () {

        gsap.to(window, {
            duration: 1,
            scrollTo: { y: "#a-propos", offsetY: 100 }, //va a a-propos avec un decallage de 100 en Y


        });
    });

    // ecarte puis remet imgparcours
    gsap.set(".imgParcours", { y: 600 });
    gsap.to(".imgParcours", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: "#a-propos",
            toggleActions: "play pause none none",
            start: "top center",

        }
    });


    //ecarte puis remet a propos blabla
    gsap.set("#a-propos-div", { y: 400 });
    gsap.to("#a-propos-div", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: "#a-propos",
            toggleActions: "play none none none",
            start: "top center",

        }
    });

    //ecarte puis remet a loisirs blabla
    gsap.set(".divLoisirs", { y: 100 });
    gsap.to(".divLoisirs", {
        y: 0,
        duration: 1,
        scrollTrigger: {
            trigger: ".sectionIndex3",
            toggleActions: "play none none none",
            start: "top center",

        }
    });

    //pouvoir bouger img loisirs
    Draggable.create(".imgLoisirs", {
        type: "x",
        bounds: ".sectionIndex3"
    });

    //ecarte puis place img loisirs
    gsap.set(".imgLoisirs:nth-child(1)", { x: -50 });
    gsap.set(".imgLoisirs:nth-child(2)", { x: -450 });
    gsap.set(".imgLoisirs:nth-child(3)", { x: -650 });
    gsap.to(".imgLoisirs", {
        x: 0,
        duration: 2,
        scrollTrigger: {
            trigger: ".sectionIndex3",
            toggleActions: "play none none none",
            start: "top center",

        }
    });

    //opacité section 1 
    gsap.set($(".sectionIndex1"), { opacity: 0 });

    gsap.to(".sectionIndex1", {
        opacity: 1,
        duration: 1,

    });

    gsap.set($(".sectionIndex2"), { opacity: 0 });

    gsap.to(".sectionIndex2", {
        opacity: 1,
        duration: 1,
        scrollTrigger: {
            trigger: "#a-propos",
            toggleActions: "play pause none none",
            start: "top center",

        }

    });

}



function experiences() {

    $("#navExperiences").addClass("underline");
    $("#navAccueil").removeClass("underline");

    gsap.set(".sectionExperiencePro", { opacity: 0 });
    gsap.to(".sectionExperiencePro", {
        opacity: 1,
        duration: 2,
    });

    const expPro = gsap.utils.toArray(".expPro");
    const expUni = gsap.utils.toArray(".expUni");

    // Coefficients ajustables selon ton design
    const coef = window.innerWidth < 768 ? 400 : window.innerWidth < 1024 ? 850 : 850;

    // Calcul dynamique basé sur le nombre d'éléments
    const endValue1 = `+=${coef * expPro.length/1.5}`;
    const endValue2 = `+=${coef * expUni.length/1.5}`;

    gsap.to(expPro, {
        xPercent: -100 * 1.2 * (expPro.length - 1),
        ease: "none",
        scrollTrigger: {
            trigger: ".sectionExperiencePro",
            pin: true,
            scrub: 1,
            snap: 1 / (expPro.length - 1),
            end: endValue1,
            start: "top top",
        }
    });

    gsap.to(expUni, {
        xPercent: -100 * 1.15 * (expUni.length - 1),
        ease: "none",
        scrollTrigger: {
            trigger: ".sectionExperienceUni",
            pin: true,
            scrub: 1,
            snap: 1 / (expUni.length - 1),
            end: endValue2,
            start: "top top",
        }
    });
}

function contact() {
    $("#navContact").addClass("underline");
    $("#navAccueil").removeClass("underline");
}








function afficherCompetencesJQuery() {
    const $langages = $(".js-langage");

    $langages.each(function (index) {
        setTimeout(() => {
            const $nomLangage = $(this).find(".js-nomLangage");
            const $progressBar = $(this).find(".js-progressBar");  //$(this) element courant 
            const $pourcentageElem = $(this).find(".js-pourcentage");

            $nomLangage.css("visibility", "visible");
            $progressBar.css("visibility", "visible");
            $pourcentageElem.css("visibility", "visible");  //@TODO enlever css de la

            const valeurFinale = parseInt($pourcentageElem.text());
            let valeurActuelle = 0;

            $pourcentageElem.text("0%");

            const interval = setInterval(() => {
                valeurActuelle++;
                $pourcentageElem.text(valeurActuelle + "%");

                if (valeurActuelle >= valeurFinale) {
                    clearInterval(interval);
                }
            }, 20);

            gsap.set($(this), { opacity: 0 });

            gsap.to($(this), {
                opacity: 1,
                duration: 1,
            });

        }, 200 * index);
    });
}

function setPourcentage() {
    const progressBars = document.querySelectorAll('.langages__progress--bar');

    progressBars.forEach(bar => {
        const pourcentage = bar.getAttribute('data-pourcent');
        bar.style.height = pourcentage + '%'; 
    });
}