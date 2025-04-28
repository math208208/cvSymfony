--
-- PostgreSQL database dump
--

-- Dumped from database version 16.8
-- Dumped by pg_dump version 16.8

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: app
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
        RETURN NEW;
    END;
$$;


ALTER FUNCTION public.notify_messenger_messages() OWNER TO app;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: admin; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.admin (
    id integer NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL
);


ALTER TABLE public.admin OWNER TO app;

--
-- Name: admin_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.admin_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_id_seq OWNER TO app;

--
-- Name: admin_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.admin_id_seq OWNED BY public.admin.id;


--
-- Name: competence; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.competence (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    pourcentage_metrise integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.competence OWNER TO app;

--
-- Name: competence_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.competence_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.competence_id_seq OWNER TO app;

--
-- Name: competence_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.competence_id_seq OWNED BY public.competence.id;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO app;

--
-- Name: experience_pro; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.experience_pro (
    id integer NOT NULL,
    user_id integer NOT NULL,
    poste character varying(255) NOT NULL,
    entreprise character varying(255) NOT NULL,
    description text NOT NULL,
    date_debut integer NOT NULL,
    date_fin integer
);


ALTER TABLE public.experience_pro OWNER TO app;

--
-- Name: experience_pro_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.experience_pro_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.experience_pro_id_seq OWNER TO app;

--
-- Name: experience_pro_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.experience_pro_id_seq OWNED BY public.experience_pro.id;


--
-- Name: experience_pro_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.experience_pro_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    poste character varying(255) NOT NULL,
    description text NOT NULL
);


ALTER TABLE public.experience_pro_translation OWNER TO app;

--
-- Name: experience_pro_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.experience_pro_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.experience_pro_translation_id_seq OWNER TO app;

--
-- Name: experience_pro_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.experience_pro_translation_id_seq OWNED BY public.experience_pro_translation.id;


--
-- Name: experience_uni; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.experience_uni (
    id integer NOT NULL,
    user_id integer NOT NULL,
    titre character varying(255) NOT NULL,
    sous_titre character varying(255) DEFAULT NULL::character varying,
    annee integer NOT NULL,
    description character varying(255) NOT NULL
);


ALTER TABLE public.experience_uni OWNER TO app;

--
-- Name: experience_uni_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.experience_uni_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.experience_uni_id_seq OWNER TO app;

--
-- Name: experience_uni_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.experience_uni_id_seq OWNED BY public.experience_uni.id;


--
-- Name: experience_uni_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.experience_uni_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    titre character varying(255) NOT NULL,
    sous_titre character varying(255) DEFAULT NULL::character varying,
    description character varying(255) NOT NULL
);


ALTER TABLE public.experience_uni_translation OWNER TO app;

--
-- Name: experience_uni_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.experience_uni_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.experience_uni_translation_id_seq OWNER TO app;

--
-- Name: experience_uni_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.experience_uni_translation_id_seq OWNED BY public.experience_uni_translation.id;


--
-- Name: formation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.formation (
    id integer NOT NULL,
    user_id integer,
    intitule character varying(255) NOT NULL,
    annee integer,
    lieu character varying(255) DEFAULT NULL::character varying,
    photo character varying(255) DEFAULT NULL::character varying,
    image_name character varying(255) DEFAULT NULL::character varying,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.formation OWNER TO app;

--
-- Name: formation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.formation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.formation_id_seq OWNER TO app;

--
-- Name: formation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.formation_id_seq OWNED BY public.formation.id;


--
-- Name: formation_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.formation_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    intitule character varying(255) NOT NULL
);


ALTER TABLE public.formation_translation OWNER TO app;

--
-- Name: formation_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.formation_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.formation_translation_id_seq OWNER TO app;

--
-- Name: formation_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.formation_translation_id_seq OWNED BY public.formation_translation.id;


--
-- Name: langage; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.langage (
    id integer NOT NULL,
    nom_langue character varying(255) NOT NULL,
    niveau character varying(255) NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.langage OWNER TO app;

--
-- Name: langage_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.langage_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.langage_id_seq OWNER TO app;

--
-- Name: langage_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.langage_id_seq OWNED BY public.langage.id;


--
-- Name: langage_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.langage_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    nom_langue character varying(255) NOT NULL
);


ALTER TABLE public.langage_translation OWNER TO app;

--
-- Name: langage_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.langage_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.langage_translation_id_seq OWNER TO app;

--
-- Name: langage_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.langage_translation_id_seq OWNED BY public.langage_translation.id;


--
-- Name: loisir; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.loisir (
    id integer NOT NULL,
    user_id integer NOT NULL,
    nom character varying(255) NOT NULL,
    image_name character varying(255) DEFAULT NULL::character varying,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.loisir OWNER TO app;

--
-- Name: loisir_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.loisir_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.loisir_id_seq OWNER TO app;

--
-- Name: loisir_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.loisir_id_seq OWNED BY public.loisir.id;


--
-- Name: loisir_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.loisir_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    nom character varying(255) NOT NULL
);


ALTER TABLE public.loisir_translation OWNER TO app;

--
-- Name: loisir_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.loisir_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.loisir_translation_id_seq OWNER TO app;

--
-- Name: loisir_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.loisir_translation_id_seq OWNED BY public.loisir_translation.id;


--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO app;

--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.messenger_messages_id_seq OWNER TO app;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: outil; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.outil (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    user_id integer NOT NULL,
    image_name character varying(255) DEFAULT NULL::character varying,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.outil OWNER TO app;

--
-- Name: outil_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.outil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.outil_id_seq OWNER TO app;

--
-- Name: outil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.outil_id_seq OWNED BY public.outil.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255) NOT NULL,
    profession character varying(255) DEFAULT NULL::character varying,
    description text NOT NULL,
    slug character varying(255) NOT NULL,
    email character varying(255) DEFAULT NULL::character varying NOT NULL,
    telephone character varying(20),
    linkdin text,
    github text,
    image_name character varying(255) DEFAULT NULL::character varying,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public."user" OWNER TO app;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_id_seq OWNER TO app;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: user_translation; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.user_translation (
    id integer NOT NULL,
    translatable_id integer NOT NULL,
    locale character varying(2) NOT NULL,
    profession character varying(255) DEFAULT NULL::character varying,
    description text NOT NULL
);


ALTER TABLE public.user_translation OWNER TO app;

--
-- Name: user_translation_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.user_translation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_translation_id_seq OWNER TO app;

--
-- Name: user_translation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.user_translation_id_seq OWNED BY public.user_translation.id;


--
-- Name: admin id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.admin ALTER COLUMN id SET DEFAULT nextval('public.admin_id_seq'::regclass);


--
-- Name: competence id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.competence ALTER COLUMN id SET DEFAULT nextval('public.competence_id_seq'::regclass);


--
-- Name: experience_pro id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro ALTER COLUMN id SET DEFAULT nextval('public.experience_pro_id_seq'::regclass);


--
-- Name: experience_pro_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro_translation ALTER COLUMN id SET DEFAULT nextval('public.experience_pro_translation_id_seq'::regclass);


--
-- Name: experience_uni id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni ALTER COLUMN id SET DEFAULT nextval('public.experience_uni_id_seq'::regclass);


--
-- Name: experience_uni_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni_translation ALTER COLUMN id SET DEFAULT nextval('public.experience_uni_translation_id_seq'::regclass);


--
-- Name: formation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation ALTER COLUMN id SET DEFAULT nextval('public.formation_id_seq'::regclass);


--
-- Name: formation_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation_translation ALTER COLUMN id SET DEFAULT nextval('public.formation_translation_id_seq'::regclass);


--
-- Name: langage id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage ALTER COLUMN id SET DEFAULT nextval('public.langage_id_seq'::regclass);


--
-- Name: langage_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage_translation ALTER COLUMN id SET DEFAULT nextval('public.langage_translation_id_seq'::regclass);


--
-- Name: loisir id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir ALTER COLUMN id SET DEFAULT nextval('public.loisir_id_seq'::regclass);


--
-- Name: loisir_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir_translation ALTER COLUMN id SET DEFAULT nextval('public.loisir_translation_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: outil id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.outil ALTER COLUMN id SET DEFAULT nextval('public.outil_id_seq'::regclass);


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Name: user_translation id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.user_translation ALTER COLUMN id SET DEFAULT nextval('public.user_translation_id_seq'::regclass);


--
-- Data for Name: admin; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.admin (id, email, roles, password) FROM stdin;
4	matheomoiron@gmail.com	["ROLE_ADMIN"]	$2y$13$3S/xhWTPRqee3XZAxkDCCew91GXKU.pmPaPNJC5LwT8ICtDlZ0.T2
\.


--
-- Data for Name: competence; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.competence (id, nom, pourcentage_metrise, user_id) FROM stdin;
13	Java	80	78
14	C	50	78
15	Python	70	78
16	PHP	60	78
17	HTML/CSS	70	78
18	JavaScript	60	78
19	PL/SQL	60	78
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20250417113540	2025-04-17 11:35:49	17
DoctrineMigrations\\Version20250417124413	2025-04-17 12:44:23	2
DoctrineMigrations\\Version20250417125808	2025-04-17 12:58:18	50
DoctrineMigrations\\Version20250417133617	2025-04-17 14:03:19	32
DoctrineMigrations\\Version20250417141928	2025-04-17 14:19:36	2
DoctrineMigrations\\Version20250417142940	2025-04-17 14:29:44	1
DoctrineMigrations\\Version20250417150211	2025-04-17 15:02:17	1
DoctrineMigrations\\Version20250417150300	2025-04-17 15:03:03	1
DoctrineMigrations\\Version20250417151144	2025-04-17 15:11:47	1
DoctrineMigrations\\Version20250417151333	2025-04-17 15:24:49	10
DoctrineMigrations\\Version20250418071752	2025-04-18 07:17:56	23
DoctrineMigrations\\Version20250418072356	2025-04-18 07:42:50	1
DoctrineMigrations\\Version20250418074244	2025-04-18 07:42:50	0
DoctrineMigrations\\Version20250418092206	2025-04-18 09:22:12	11
DoctrineMigrations\\Version20250418092729	2025-04-18 09:27:36	14
DoctrineMigrations\\Version20250418093138	2025-04-18 09:31:42	1
DoctrineMigrations\\Version20250418131255	2025-04-18 13:13:01	7
DoctrineMigrations\\Version20250418131520	2025-04-18 13:15:25	1
DoctrineMigrations\\Version20250418131658	2025-04-18 13:17:03	11
DoctrineMigrations\\Version20250422132143	2025-04-22 13:22:07	17
DoctrineMigrations\\Version20250422134751	2025-04-22 13:47:56	16
DoctrineMigrations\\Version20250423093347	2025-04-23 09:33:54	13
DoctrineMigrations\\Version20250423094114	2025-04-23 09:41:17	1
DoctrineMigrations\\Version20250423095329	2025-04-23 09:53:35	17
DoctrineMigrations\\Version20250423100120	2025-04-23 10:01:23	18
DoctrineMigrations\\Version20250423100336	2025-04-23 10:03:39	1
DoctrineMigrations\\Version20250423100523	2025-04-23 10:05:25	1
DoctrineMigrations\\Version20250423101136	2025-04-23 10:11:45	1
DoctrineMigrations\\Version20250423101142	2025-04-23 10:11:45	0
DoctrineMigrations\\Version20250423102356	2025-04-23 10:23:58	12
DoctrineMigrations\\Version20250423123224	2025-04-23 12:32:32	3
DoctrineMigrations\\Version20250423131458	2025-04-23 13:15:01	1
DoctrineMigrations\\Version20250423141014	2025-04-23 14:10:17	14
DoctrineMigrations\\Version20250423144347	2025-04-23 14:43:50	1
DoctrineMigrations\\Version20250424073039	2025-04-24 07:30:45	3
DoctrineMigrations\\Version20250424073251	2025-04-24 07:32:53	1
DoctrineMigrations\\Version20250424074128	2025-04-24 07:41:32	1
DoctrineMigrations\\Version20250424075029	2025-04-24 07:50:32	1
DoctrineMigrations\\Version20250424075108	2025-04-24 07:51:11	1
DoctrineMigrations\\Version20250424075120	2025-04-24 07:51:22	1
DoctrineMigrations\\Version20250424075336	2025-04-24 07:53:38	13
DoctrineMigrations\\Version20250424075533	2025-04-24 07:55:41	1
DoctrineMigrations\\Version20250424075737	2025-04-24 07:57:41	1
DoctrineMigrations\\Version20250424080339	2025-04-24 08:03:42	11
DoctrineMigrations\\Version20250424080611	2025-04-24 08:06:13	11
DoctrineMigrations\\Version20250424080725	2025-04-24 08:07:26	10
DoctrineMigrations\\Version20250424082516	2025-04-24 08:25:20	3
DoctrineMigrations\\Version20250424091227	2025-04-24 09:12:31	2
DoctrineMigrations\\Version20250424092739	2025-04-24 09:27:42	20
DoctrineMigrations\\Version20250424101332	2025-04-24 10:13:36	20
DoctrineMigrations\\Version20250424102254	2025-04-24 10:22:58	1
DoctrineMigrations\\Version20250424123258	2025-04-24 15:01:27	1
\.


--
-- Data for Name: experience_pro; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.experience_pro (id, user_id, poste, entreprise, description, date_debut, date_fin) FROM stdin;
9	78	Equipier polyvalent	McDonald’s - Maison Dieu (89)	<ul>\r\n\t<li>Travail en &eacute;quipe</li>\r\n\t<li>R&eacute;alisation de diverses t&acirc;ches</li>\r\n\t<li>Services clients</li>\r\n\t<li>Respect des r&egrave;gles d&rsquo;hygi&egrave;ne et de s&eacute;curit&eacute;</li>\r\n</ul>	2023	2024
10	78	Stage développeur web	Infostrates - Marseille (13)	<ul>\r\n\t<li>XXXXX</li>\r\n\t<li>XXXXXXXXXXX</li>\r\n\t<li>XXXXXX XXXXXXXXXXX</li>\r\n\t<li>XXxXXXXX</li>\r\n\t<li>XXXXXXXXX</li>\r\n</ul>	2025	2025
17	80	Ouvrier agricole	Ferme de la Vallée Verte, Dijon, France	sdvsvdvsdsvdsvdsvddsvsvdvdsvdsv\r\nvsdvdsvdsvvdvsvd\r\nsvddsvdsvdssvdvds\r\nvsdsvddddddddsv	2023	2024
\.


--
-- Data for Name: experience_pro_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.experience_pro_translation (id, translatable_id, locale, poste, description) FROM stdin;
17	9	es	Operario polivalente	Trabajo en equipo\r\nRealización de tareas diversas\r\nAtención al cliente\r\nCumplimiento de las normas de salud y seguridad
18	9	en	Multi-skilled operator	Working in a team\r\nVarious tasks\r\nCustomer service\r\nCompliance with health and safety rules
22	10	en	Web developer internship	XXXXX\r\nXXXXXXXXXXX\r\nXXXXXX XXXXXXXXXXX\r\nXXxXXXXX XXXXXXXXXXXXX
23	10	es	Prácticas de desarrollador web	XXXXX\r\nXXXXXXXXXXX\r\nXXXXXX XXXXXXXXXXX\r\nXXxXXXXX XXXXXXXXXXXXX
\.


--
-- Data for Name: experience_uni; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.experience_uni (id, user_id, titre, sous_titre, annee, description) FROM stdin;
12	78	Réalisation d’un jeu de paires	Equipe projet de 4 personnes	2024	<ul>\r\n\t<li>Travail en &eacute;quipe</li>\r\n\t<li>Conception et programmation en langage C sous Linux</li>\r\n\t<li>Cr&eacute;ation d&rsquo;une page web en HTML/CSS d&eacute;crivant la sp&eacute;cification technique du d&eacute;veloppement</li>\r\n</ul>
13	78	Gestion d’un projet	Equipe projet de 8 personnes	2024	<ul>\r\n\t<li>Analyser les besoins de l&#39;entreprise</li>\r\n\t<li>R&eacute;diger un cahier des charges</li>\r\n\t<li>R&eacute;diger un dossier de gestion de projet</li>\r\n</ul>
14	78	Développement d’une application	Equipe projet de 6 personnes	2024	<ul>\r\n\t<li>Cr&eacute;ation d&rsquo;une librairie permettant de compresser des fichiers HTML et CSS</li>\r\n\t<li>Conception et programmation orient&eacute;e objet en Java</li>\r\n\t<li>Proc&eacute;dures qualit&eacute;</li>\r\n</ul>
15	78	Développement d’une application	Equipe projet de 6 personnes	2024	<ul>\r\n\t<li>Convertir un jeu de plateau en version num&eacute;rique</li>\r\n\t<li>Conception et programmation orient&eacute;e objet en Java</li>\r\n\t<li>Proc&eacute;dures qualit&eacute;</li>\r\n</ul>
16	78	Développement d’un ERP	Equipe projet de 6 personnes	2024	<ul>\r\n\t<li>Organisation et conception d&rsquo;un projet</li>\r\n\t<li>D&eacute;veloppement de l&rsquo;ERP</li>\r\n\t<li>Utilisation de la m&eacute;thode AGILE</li>\r\n</ul>
\.


--
-- Data for Name: experience_uni_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.experience_uni_translation (id, translatable_id, locale, titre, sous_titre, description) FROM stdin;
2	12	en	Making a set of pairs	4-person project team	Teamwork\r\nDesign and programming in C under Linux\r\nCreation of a web page in HTML/CSS describing the technical specification of the development
3	12	es	Hacer un juego de parejas	Equipo de proyecto de 4 personas	Trabajo en equipo\r\nDiseño y programación en C bajo Linux\r\nCreación de una página web en HTML/CSS describiendo la especificación técnica del desarrollo
5	13	es	Gestión de proyectos	Equipo de proyecto de 8 personas	Analizar las necesidades de la empresa\r\nElaborar el pliego de condiciones\r\nRedactar un expediente de gestión del proyecto
6	12	en	Project management	8-person project team	Analyze the company s needs\r\nDraw up specifications\r\nWrite a project management file
7	14	en	Application development	6-person project team	Creation of a library for compressing HTML and CSS files\r\nDesign and object-oriented programming in Java\r\nQuality procedures
8	14	es	Desarrollo de aplicaciones	Equipo de proyecto de 6 personas	Creación de una librería para la compresión de archivos HTML y CSS\r\nDiseño y programación orientada a objetos en Java\r\nProcedimientos de calidad
9	15	es	Desarrollo de aplicaciones	Equipo de proyecto de 6 personas	Conversión de un juego de mesa en una versión digital\r\nDiseño y programación orientada a objetos en Java\r\nProcedimientos de calidad
10	15	en	Application development	6-person project team	Converting a board game into a digital version\r\nDesign and object-oriented programming in Java\r\nQuality procedures
11	16	en	ERP development	6-person project team	Project organization and design\r\nERP development\r\nUse of the AGILE method
12	16	es	Desarrollo de ERP	Equipo de proyecto formado por 6 personas	Organización y diseño del proyecto\r\nDesarrollo del ERP\r\nUtilización del método AGILE
\.


--
-- Data for Name: formation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.formation (id, user_id, intitule, annee, lieu, photo, image_name, updated_at) FROM stdin;
10	78	1 année de BUT Informatique	2024	IUT Nice (06), UNICA	\N	iut-6809f48d1b461932415411.png	2025-04-24 08:21:33
9	78	Baccalauréat général \t(Mention Bien),(Spécialité NSI, Maths et SES),	2023	Lycée Jacques Amyot, Auxerre (89)	\N	lycee-6809f49a8e8e4453362872.jpg	2025-04-24 08:21:46
11	78	, 2 année de BUT Informatique (Parcours réalisation d applications ...),	2025	IUT Nice (06), UNICA	\N	tracteur-6809fb90d856d189656123.jpg	2025-04-24 08:51:28
\.


--
-- Data for Name: formation_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.formation_translation (id, translatable_id, locale, intitule) FROM stdin;
5	10	es	1 año de BUT Informatique
6	10	en	1 year of BUT Informatique
7	9	en	Baccalauréat général (with honors), (NSI, Math and SES specialties),
8	9	es	Bachillerato General (con matrícula de honor), (especialidad en NSI, Matemáticas y SES),
10	11	en	2nd year of a Computer Science degree (Application development course ...),
11	11	es	2º curso de una licenciatura en Informática (desarrollo de aplicaciones),
\.


--
-- Data for Name: langage; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.langage (id, nom_langue, niveau, user_id) FROM stdin;
6	Anglais	B1	78
7	Espagnol	A1	78
\.


--
-- Data for Name: langage_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.langage_translation (id, translatable_id, locale, nom_langue) FROM stdin;
5	6	es	Inglés
6	6	en	English
7	7	en	Spanish
8	7	es	Español
\.


--
-- Data for Name: loisir; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.loisir (id, user_id, nom, image_name, updated_at) FROM stdin;
12	78	Gymnastique (8 ans en club)	gym-6809f0adeb684462592779.jpg	2025-04-24 08:05:01
13	78	Jeux vidéo	minecraft-6809f46d79cf1127061453.jpg	2025-04-24 08:21:01
15	78	Musique	47ter-6809fabbdc50b080221035.jpg	2025-04-24 08:47:55
14	78	Cuisine	\N	\N
\.


--
-- Data for Name: loisir_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.loisir_translation (id, translatable_id, locale, nom) FROM stdin;
2	14	en	cooking
3	14	es	cocinero
6	13	en	video games
7	13	es	videojuegos
8	15	es	música
10	15	en	music
12	12	es	gimnasia
13	12	en	gymnastics
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: outil; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.outil (id, nom, user_id, image_name, updated_at) FROM stdin;
10	Git	78	git-6809f59378995048332628.png	2025-04-24 08:25:55
15	PhpMyAdmin	78	phpmyadmin-6809f5999c9f6428299843.png	2025-04-24 08:26:01
11	IntelliJ	78	intellij-6809f5a006a96858016888.jpg	2025-04-24 08:26:08
12	Eclipse	78	eclipse-6809f5a596caa267670657.jpg	2025-04-24 08:26:13
13	VSCode	78	vscode-6809f5af2b287196655515.png	2025-04-24 08:26:23
14	AndroidStudio	78	android-6809f5b48ef8f543474538.png	2025-04-24 08:26:28
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public."user" (id, nom, prenom, profession, description, slug, email, telephone, linkdin, github, image_name, updated_at) FROM stdin;
28	Duhamel	Charles	Eleveur dinsectes	Iure quis autem et rerum vero. Suscipit accusantium est et illum et. Eaque laudantium et et cumque. Commodi architecto eum quam minus quae.	charles-duhamel	georges89@orange.fr	0378059821	https://linkedin.com/in/pbousquet	https://github.com/carlier.patrick	\N	\N
29	Maurice	Gabrielle	Monteur en siège	Reprehenderit cum aperiam atque autem tempora fugit nostrum. Laudantium rerum est qui dolore et quisquam.	gabrielle-maurice	besson.zacharie@laposte.net	0695470227	https://linkedin.com/in/vlaurent	https://github.com/chevallier.roland	\N	\N
30	Chauvin	Emmanuel	Porteur de hottes	Est accusamus est molestiae aut temporibus nobis ad. Voluptatem saepe voluptates ex facere. Mollitia qui dolore in qui in incidunt. Quia omnis nemo illum qui repudiandae nam eveniet.	emmanuel-chauvin	pboyer@legendre.fr	02 27 71 79 59	https://linkedin.com/in/maryse.meyer	https://github.com/tristan.bertin	\N	\N
31	Philippe	Catherine	Tôlier-traceur	Fugiat alias perferendis quidem sint architecto ipsam. Dolore incidunt vero praesentium omnis natus. Possimus dicta similique quia est quod tempore. Accusantium ducimus excepturi voluptatem sint ut. Sint ut magnam provident fuga odio accusamus.	catherine-philippe	gabriel31@paul.org	+33 9 70 30 10 89	https://linkedin.com/in/oceane88	https://github.com/edith96	\N	\N
32	Weber	Maurice	Géologue prospecteur	Quae harum nisi perferendis quo. Velit excepturi et ea hic maiores aut et. Tempora laboriosam maxime quia deserunt qui reiciendis. Consectetur quis quis aspernatur officia magnam.	maurice-weber	alfred82@nguyen.fr	+33 (0)9 54 02 62 06	https://linkedin.com/in/colas.timothee	https://github.com/dandre	\N	\N
33	Bonneau	Sylvie	Comptable unique	Debitis perferendis qui sequi nostrum ea voluptatem. Voluptatem molestias et iusto ipsa. Voluptas odio libero ipsum voluptate ex nihil molestias. Non ea non numquam animi quam beatae.	sylvie-bonneau	qlouis@free.fr	0999896233	https://linkedin.com/in/veronique.verdier	https://github.com/jguillet	\N	\N
34	Dijoux	Françoise	Vendeur en meubles	Cumque itaque voluptas accusantium consectetur. Voluptas ad nesciunt est quibusdam vel error.	francoise-dijoux	alexandre.samson@laposte.net	+33 (0)3 12 11 57 59	https://linkedin.com/in/dumas.marthe	https://github.com/albert.bertrand	\N	\N
35	Thierry	Émile	Fossoyeur	Consequuntur vel recusandae voluptas aut inventore qui cupiditate. Ut accusamus temporibus quo placeat quos. Enim provident dolorem et blanditiis quasi quia inventore. Et id fugit aut deleniti aut in quis voluptatem. Placeat in enim aut beatae velit.	Émile-thierry	xlaurent@bouvet.com	+33 1 98 95 67 69	https://linkedin.com/in/coulon.brigitte	https://github.com/isaac.benard	\N	\N
37	Vallet	Rémy	Instituteur	Numquam eaque unde asperiores et veniam. Eum distinctio odit voluptates qui sit repudiandae. Numquam soluta ad sint. Repudiandae et mollitia ipsam et suscipit consequatur.	remy-vallet	colette.bonnet@lefevre.fr	0186858254	https://linkedin.com/in/dupuy.augustin	https://github.com/michel40	\N	\N
38	Torres	Anaïs	Mouliste drapeur	Magni quia et aspernatur nisi autem. Exercitationem quia voluptate sint temporibus asperiores temporibus voluptas. Illo inventore natus ut iste eveniet. Esse repellat tenetur et ipsam qui.	anais-torres	arnaude.boutin@boyer.net	0542332472	https://linkedin.com/in/rchretien	https://github.com/ubarthelemy	\N	\N
39	Blot	Julien	Story boarder	Voluptatibus totam sed sed debitis aut occaecati nisi. A cumque voluptatem enim dignissimos et quibusdam. Quos deserunt magni iste ut animi. Nemo enim non corporis eius nulla vero excepturi.	julien-blot	jacqueline.lecomte@paris.fr	01 14 05 66 59	https://linkedin.com/in/hbonneau	https://github.com/camille.roger	\N	\N
40	Bouvet	Thibaut	Miroitier	Sit nulla et quod molestias labore. Nobis et eaque omnis doloremque. Laudantium est libero harum tempora eum accusantium numquam. Velit ratione illo est.	thibaut-bouvet	umartel@remy.fr	0343871558	https://linkedin.com/in/merle.victor	https://github.com/jbreton	\N	\N
42	Clerc	Martin	Assistant styliste	Aut et non et aspernatur mollitia perferendis. Voluptatem non qui vel et labore incidunt in. Esse praesentium rerum qui vitae est ea esse.	martin-clerc	colette.diaz@parent.net	+33 2 05 65 46 30	https://linkedin.com/in/umendes	https://github.com/emmanuelle60	\N	\N
43	Boyer	Henri	Solier-moquettiste	Aliquid ad dolor possimus rerum et nam blanditiis. Rerum quisquam quas et libero dicta molestiae. Consectetur atque delectus provident qui.	henri-boyer	claire.chevallier@gaudin.fr	0158813943	https://linkedin.com/in/barthelemy.jules	https://github.com/xgaillard	\N	\N
44	Jacquet	Isaac	Pyrotechnicien	Ratione nemo deleniti alias ut consequatur omnis. Dolorem accusamus velit soluta voluptatibus et corrupti similique. Ut harum doloremque eius saepe.	isaac-jacquet	claudine13@live.com	+33 4 83 33 46 33	https://linkedin.com/in/alphonse.descamps	https://github.com/legall.andre	\N	\N
45	Thomas	Constance	Verrier dart	Voluptas officiis et voluptate itaque laborum incidunt. Occaecati enim illum atque quos. Ullam nihil doloremque rerum dignissimos.	constance-thomas	fjoly@maury.org	05 75 37 27 83	https://linkedin.com/in/margaud.blanchet	https://github.com/marty.hortense	\N	\N
41	Dubois	Geneviève	Logistique	Placeat consequatur doloremque asperiores soluta dolorem alias exercitationem. Molestiae amet minima asperiores blanditiis odit doloribus odio. Accusantium voluptas eos provident numquam sint sed rerum.	genevieve-dubois	pmeyer@aubert.fr	01 15 45 64 39	https://linkedin.com/in/lorraine.boyer	https://github.com/xavier.roger	\N	2025-04-24 08:10:14
46	Clerc	Thierry	Verrier à la main	Dolore aut eum quaerat dignissimos numquam corporis. Sequi dolores sed iure aut facere quae illum officia. Et atque aut est.	thierry-clerc	camille02@wanadoo.fr	0754604002	https://linkedin.com/in/maggie37	https://github.com/gomez.charles	\N	\N
47	Rolland	Margot	Premier clerc	Asperiores molestiae nostrum voluptas pariatur maiores. Aut nihil provident eum perferendis impedit est dolorum. Soluta quod est labore autem velit qui sequi qui.	margot-rolland	thibaut.dupont@lebon.com	0190433097	https://linkedin.com/in/fnavarro	https://github.com/clemence69	\N	\N
48	Lemaitre	Élodie	Elagueur-grimpeur	Vitae eligendi delectus quaerat quis nihil unde. Fugiat voluptatem amet esse. Ut facere sit sit sit omnis.	Élodie-lemaitre	auguste.alexandre@wanadoo.fr	+33 (0)1 34 10 53 03	https://linkedin.com/in/isabelle41	https://github.com/vchevalier	\N	\N
49	Salmon	Maggie	Ingénieur logistique	Debitis ipsam a est non omnis omnis. Eos reprehenderit eligendi corrupti amet dolores. Ut accusantium quibusdam vel molestiae totam. Modi eligendi provident ad qui molestiae quam.	maggie-salmon	jacques.gillet@sfr.fr	+33 6 35 43 43 69	https://linkedin.com/in/edouard.jacquet	https://github.com/gabrielle.berthelot	\N	\N
50	Lebrun	Clémence	Radio chargeur	Et voluptatum perferendis aut qui veritatis aliquid. Commodi et et est deleniti. Ducimus officiis qui dolores velit. Id dolorum dicta adipisci officiis nesciunt veritatis corrupti. Voluptatem expedita natus vel qui corrupti.	clemence-lebrun	anouk.lebrun@laposte.net	+33 (0)9 86 02 20 40	https://linkedin.com/in/emmanuel.boulay	https://github.com/pauline68	\N	\N
51	Collin	Michelle	Monteur-frigoriste	Officiis sed corrupti recusandae ut. Dolor sit enim natus officia qui. Labore molestiae error voluptates.	michelle-collin	etienne.guillou@sfr.fr	+33 2 44 79 69 59	https://linkedin.com/in/olivier37	https://github.com/thierry.adrien	\N	\N
52	Lopes	Gérard	Agent denquêtes	Inventore nihil voluptatem architecto quidem et adipisci. Enim odio cupiditate placeat quibusdam architecto ducimus magni et. Provident molestias minus quod aliquid exercitationem nobis commodi doloremque. Doloremque voluptatibus nisi eos expedita.	gerard-lopes	diane97@guillot.fr	+33 (0)1 93 38 33 01	https://linkedin.com/in/kriou	https://github.com/hardy.suzanne	\N	\N
53	Hubert	Michelle	Corniste	Libero qui sequi vel aspernatur et. Aut tenetur labore sit vitae molestiae eaque. Adipisci est possimus eius excepturi qui laboriosam. Perferendis non deserunt aut eos dolore.	michelle-hubert	rene.masson@free.fr	+33 (0)2 96 96 65 95	https://linkedin.com/in/obousquet	https://github.com/celine.rodriguez	\N	\N
54	Riou	Louise	Manager dartiste	Ipsam occaecati dolores sapiente. Soluta molestias molestiae odit doloribus ut explicabo autem. Quia ut veritatis labore dolor et consectetur.	louise-riou	zleblanc@godard.net	+33 7 63 98 66 24	https://linkedin.com/in/benjamin27	https://github.com/therese78	\N	\N
55	Poulain	Marcelle	Galeriste	Corrupti mollitia sunt consectetur. Ut est et incidunt. Explicabo qui possimus ea. Perferendis alias eos unde sint exercitationem consequuntur et deleniti.	marcelle-poulain	payet.maryse@live.com	05 60 66 03 65	https://linkedin.com/in/maurice.dupuis	https://github.com/upaul	\N	\N
56	Garnier	Nathalie	Sertisseur	Consequuntur quibusdam magnam cumque. Vero et libero possimus quia. Ipsum totam maxime dolorem dolorem cupiditate voluptates labore. Iure non libero quia aliquam ipsam.	nathalie-garnier	ypoulain@noos.fr	0829112002	https://linkedin.com/in/aime35	https://github.com/rroyer	\N	\N
57	Mendes	Philippe	Etancheur-bardeur	Quo ipsam consequuntur explicabo odio dolorum aliquid voluptas. Eos fuga repellendus ut provident rerum sed. Quam molestiae porro natus molestiae praesentium iure aut occaecati.	philippe-mendes	xperrin@moreau.net	+33 7 38 46 03 96	https://linkedin.com/in/bourdon.henri	https://github.com/brigitte36	\N	\N
58	Cousin	Hugues	Délégué à la tutelle	Sit quasi totam autem consequuntur doloribus dicta odit. Quasi deserunt rerum vel eos id voluptatibus sit. Sequi ea rerum voluptatem neque aut.	hugues-cousin	mathieu.emmanuelle@monnier.org	+33 (0)1 57 15 47 77	https://linkedin.com/in/omartins	https://github.com/olivie.raymond	\N	\N
59	Godard	Roland	Commis de coupe	Incidunt aut fugiat et aut et veniam. Est saepe voluptatem sunt amet esse deserunt. Aut nulla et amet quia qui. Omnis molestiae sunt ab corrupti et molestias.	roland-godard	zfrancois@fouquet.com	07 56 72 71 14	https://linkedin.com/in/ogay	https://github.com/rodriguez.jacques	\N	\N
60	Guerin	Benjamin	Matelassier	Sit non rerum eaque ipsam. Quidem quae repellendus quae non placeat ipsa. Ut id excepturi similique deserunt.	benjamin-guerin	thibault.sauvage@gosselin.com	0113615518	https://linkedin.com/in/genevieve.chevallier	https://github.com/stephane.charpentier	\N	\N
61	Denis	Guy	Gynécologue	Ad occaecati et qui cupiditate eaque. In inventore repudiandae rem temporibus soluta unde officia. Vel rerum consectetur rem aut eos maxime quo.	guy-denis	margaux.collin@tele2.fr	+33 (0)2 00 84 46 39	https://linkedin.com/in/josette.thierry	https://github.com/susan25	\N	\N
62	Gonzalez	Christophe	Finance	Ea fugiat sapiente reprehenderit quisquam. Nam voluptatem qui ab sed vero natus adipisci. Illum nihil molestias commodi quos non autem. Sint eum tenetur aut enim consequuntur omnis eaque.	christophe-gonzalez	thibaut.baudry@yahoo.fr	+33 (0)6 30 45 69 51	https://linkedin.com/in/jgrenier	https://github.com/auguste.collin	\N	\N
63	Valentin	Joseph	Rédacteur des débats	Possimus quisquam temporibus fuga quia voluptatem. Qui quis occaecati enim tenetur repellat. Ut porro est delectus enim eum nesciunt. Vitae quia quo voluptatem.	joseph-valentin	alexandria.robert@noos.fr	+33 (0)8 14 98 80 40	https://linkedin.com/in/wperret	https://github.com/chantal.leclercq	\N	\N
64	Jacquot	Alphonse	Employé détage	Est voluptates maxime omnis commodi molestiae voluptas. Aperiam nobis sed ea ratione. Exercitationem magni est officiis aliquid aut.	alphonse-jacquot	richard13@vallee.com	+33 (0)1 25 51 96 02	https://linkedin.com/in/allain.luc	https://github.com/olivier.valerie	\N	\N
65	Leconte	Madeleine	Mannequin détail	Facere dolore ut est magni impedit architecto. Cupiditate et qui fugiat at iste deserunt animi. Voluptatem nemo consequuntur ea commodi eveniet voluptas dolor qui. Eveniet suscipit perspiciatis ipsa voluptates facere maxime. Perspiciatis quidem sit quis aspernatur mollitia ut beatae architecto.	madeleine-leconte	antoinette51@payet.fr	+33 (0)8 09 06 47 58	https://linkedin.com/in/gabrielle.masson	https://github.com/flemoine	\N	\N
66	Roy	Valentine	Etancheur-bardeur	Omnis iusto at impedit nostrum facere qui et. Culpa amet temporibus qui delectus facere. Sed cumque consequatur quia qui enim adipisci laudantium sed.	valentine-roy	nicole85@gomez.com	+33 7 76 39 79 47	https://linkedin.com/in/josette.dias	https://github.com/dufour.laurent	\N	\N
67	Philippe	Michelle	Turbinier	Architecto autem et similique quidem. Ullam nam recusandae dolore non aliquam ab ratione. Corrupti quia hic magnam voluptates eum eius consequatur repellendus. Sit eum et non possimus.	michelle-philippe	marc57@club-internet.fr	01 37 22 08 80	https://linkedin.com/in/martine97	https://github.com/michelle.ramos	\N	\N
68	Delmas	Marianne	Chanteur	Voluptatem est ut qui rerum. Quas facere est quis asperiores dolore illo. Debitis est occaecati enim illum animi praesentium. Ut qui ullam veritatis nihil tempora. Veniam omnis eaque esse dolorem eos consectetur.	marianne-delmas	valerie67@sfr.fr	0543425876	https://linkedin.com/in/claude74	https://github.com/lefebvre.nicolas	\N	\N
69	Prevost	Céline	Professeur ditalien	Nostrum aut et natus id vitae sed perferendis. Incidunt doloribus quia praesentium et dolorem earum est. Quo maiores corrupti voluptatem qui natus. Commodi dolore et dolorem delectus optio.	celine-prevost	xperrin@perez.com	+33 5 44 78 82 80	https://linkedin.com/in/ylemonnier	https://github.com/franck58	\N	\N
70	Roussel	Nathalie	Pizzaïolo	Voluptas non et consequatur. Eaque veritatis corporis dolorum. Pariatur sequi illo laborum quidem.	nathalie-roussel	lpayet@gmail.com	+33 1 81 17 76 98	https://linkedin.com/in/arthur85	https://github.com/ines17	\N	\N
71	Lemaitre	Matthieu	Paléontologue	Dignissimos velit quia quisquam nisi ut ipsa. Praesentium officiis provident alias veritatis. Dolores omnis voluptatem qui. Aut cupiditate officiis sit delectus unde magnam est.	matthieu-lemaitre	marchal.oceane@diallo.fr	02 24 42 98 61	https://linkedin.com/in/omorvan	https://github.com/louis.etienne	\N	\N
72	Gomez	Noémi	Mannequin détail	Eaque voluptatum ducimus enim est qui. Quibusdam neque ut eum impedit ut ut aut cum.	noemi-gomez	gauthier.leon@gmail.com	+33 (0)3 08 37 29 80	https://linkedin.com/in/olivier.antoine	https://github.com/valerie.rossi	\N	\N
73	Hardy	Julien	Manager dartiste	Eveniet dolores et neque iusto at minus. Excepturi nihil laboriosam aut dolorum repellat aspernatur. Ut quia mollitia sint et aut odit. Maiores illum veritatis enim est reprehenderit.	julien-hardy	jgilbert@live.com	+33 (0)8 95 09 05 39	https://linkedin.com/in/zdubois	https://github.com/claire86	\N	\N
74	Laroche	Adrienne	Relieur-doreur	Minus beatae error voluptas non officiis illum. Delectus quo ut ut nihil. Est ut dolore non laborum odio ut architecto. Consequatur nesciunt necessitatibus sit a itaque rerum.	adrienne-laroche	adrienne11@perrin.com	+33 (0)1 43 63 84 85	https://linkedin.com/in/daniel.marechal	https://github.com/chantal35	\N	\N
75	Vaillant	Valentine	Copiste offset	Temporibus maiores occaecati et qui error quam omnis. Exercitationem quo adipisci rerum possimus omnis qui neque. Aut iure soluta aut sit necessitatibus. Atque nulla voluptatem similique minus optio velit.	valentine-vaillant	adrienne90@michel.fr	0632826044	https://linkedin.com/in/zacharie58	https://github.com/alexandria.loiseau	\N	\N
76	Pasquier	Joseph	Conseiller culinaire	Omnis ut fugit ut perspiciatis voluptas. Voluptas ducimus quae dolorem similique illum doloremque temporibus. Corrupti et sint rerum sunt dolorem.	joseph-pasquier	ibaudry@live.com	+33 (0)2 24 70 64 23	https://linkedin.com/in/bernier.veronique	https://github.com/remy.david	\N	\N
77	Mahe	Charlotte	Chef de fabrication	Harum labore repellat fugiat cum accusamus. Est numquam cumque expedita nulla laboriosam exercitationem ad. Dicta quo ut eligendi ut. Dicta a vel consequatur rerum. Sed odio nisi amet sit magnam.	charlotte-mahe	christophe.sanchez@henry.fr	0440748938	https://linkedin.com/in/laetitia.maury	https://github.com/zoe.salmon	\N	\N
81	fgfg	fdsdgfd	gsdfggfdfgd	fdggfdsgdfsfdg	fdsdgfd-fgfg	fdgdsfsdfggfdgfdgfd	\N	\N	\N	drone-680a0fb72f990781703154.jpg	2025-04-24 10:17:27
82	ssss	ssss	ssss	ssss	ssss-ssss	ssss	\N	\N	\N	\N	\N
80	Dupont	Jean	Agriculteur	Je suis un agriculteur passionné par mon métier et la gestion dune exploitation agricole durable. Depuis plusieurs années, je mengage dans des pratiques respectueuses de lenvironnement tout en cherchant à améliorer lefficacité et la rentabilité de mes cultures. Jai acquis de solides compétences en gestion des terres, en entretien des équipements agricoles et en supervision déquipes de travail. Mon parcours ma également permis de me former à la maintenance des matériels agricoles et à la gestion dexploitation.	jean-dupont	jeandupont@gmail.com	0765456798	\N	\N	default-6809f279cb7a3876467136.png	2025-04-24 08:12:41
78	Moiron	Mathéo	Etudiant	<p>Je sais pas encore quoi mettre la mais au moins un truc du genre math&eacute;o <u><span style="font-family:Arial,Helvetica,sans-serif">20 ans de Nice </span></u>(a faire evoluer en fonction de la recherche sinon)</p>	matheo-moiron	matheomoiron@gmail.com	0659380569	\N	\N	moi-6809f15a26570553480471.jpg	2025-04-24 08:07:54
\.


--
-- Data for Name: user_translation; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.user_translation (id, translatable_id, locale, profession, description) FROM stdin;
5	78	en	Student	I dont know what to put here yet, but at least something along the lines of math 20 ans de Nice (to evolve with research if not).
4	78	es	Estudiante	Todavía no sé qué poner aquí, pero al menos algo en la línea de 20 años de matemáticas de Niza (a actualizar según las investigaciones por otra parte)
\.


--
-- Name: admin_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval(public.admin_id_seq, 4, true);


--
-- Name: competence_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.competence_id_seq', 19, true);


--
-- Name: experience_pro_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.experience_pro_id_seq', 17, true);


--
-- Name: experience_pro_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.experience_pro_translation_id_seq', 23, true);


--
-- Name: experience_uni_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.experience_uni_id_seq', 16, true);


--
-- Name: experience_uni_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.experience_uni_translation_id_seq', 12, true);


--
-- Name: formation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.formation_id_seq', 20, true);


--
-- Name: formation_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.formation_translation_id_seq', 11, true);


--
-- Name: langage_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.langage_id_seq', 11, true);


--
-- Name: langage_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.langage_translation_id_seq', 8, true);


--
-- Name: loisir_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.loisir_id_seq', 22, true);


--
-- Name: loisir_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.loisir_translation_id_seq', 13, true);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: outil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.outil_id_seq', 16, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.user_id_seq', 82, true);


--
-- Name: user_translation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.user_translation_id_seq', 5, true);


--
-- Name: admin admin_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT admin_pkey PRIMARY KEY (id);


--
-- Name: competence competence_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.competence
    ADD CONSTRAINT competence_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: experience_pro experience_pro_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro
    ADD CONSTRAINT experience_pro_pkey PRIMARY KEY (id);


--
-- Name: experience_pro_translation experience_pro_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro_translation
    ADD CONSTRAINT experience_pro_translation_pkey PRIMARY KEY (id);


--
-- Name: experience_uni experience_uni_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni
    ADD CONSTRAINT experience_uni_pkey PRIMARY KEY (id);


--
-- Name: experience_uni_translation experience_uni_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni_translation
    ADD CONSTRAINT experience_uni_translation_pkey PRIMARY KEY (id);


--
-- Name: formation formation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation
    ADD CONSTRAINT formation_pkey PRIMARY KEY (id);


--
-- Name: formation_translation formation_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation_translation
    ADD CONSTRAINT formation_translation_pkey PRIMARY KEY (id);


--
-- Name: langage langage_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage
    ADD CONSTRAINT langage_pkey PRIMARY KEY (id);


--
-- Name: langage_translation langage_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage_translation
    ADD CONSTRAINT langage_translation_pkey PRIMARY KEY (id);


--
-- Name: loisir loisir_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir
    ADD CONSTRAINT loisir_pkey PRIMARY KEY (id);


--
-- Name: loisir_translation loisir_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir_translation
    ADD CONSTRAINT loisir_translation_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: outil outil_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.outil
    ADD CONSTRAINT outil_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: user_translation user_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.user_translation
    ADD CONSTRAINT user_translation_pkey PRIMARY KEY (id);


--
-- Name: idx_1d728cfa2c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_1d728cfa2c2ac5d3 ON public.user_translation USING btree (translatable_id);


--
-- Name: idx_22627a3ea76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_22627a3ea76ed395 ON public.outil USING btree (user_id);


--
-- Name: idx_2c533f8c2c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_2c533f8c2c2ac5d3 ON public.experience_uni_translation USING btree (translatable_id);


--
-- Name: idx_3fa504be2c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_3fa504be2c2ac5d3 ON public.langage_translation USING btree (translatable_id);


--
-- Name: idx_404021bfa76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_404021bfa76ed395 ON public.formation USING btree (user_id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_94d4687fa76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_94d4687fa76ed395 ON public.competence USING btree (user_id);


--
-- Name: idx_a52ce209a76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_a52ce209a76ed395 ON public.experience_pro USING btree (user_id);


--
-- Name: idx_acf3d88aa76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_acf3d88aa76ed395 ON public.experience_uni USING btree (user_id);


--
-- Name: idx_b377a1182c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_b377a1182c2ac5d3 ON public.loisir_translation USING btree (translatable_id);


--
-- Name: idx_cc50ea26a76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_cc50ea26a76ed395 ON public.langage USING btree (user_id);


--
-- Name: idx_cf3b2060a76ed395; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_cf3b2060a76ed395 ON public.loisir USING btree (user_id);


--
-- Name: idx_eb9465b42c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_eb9465b42c2ac5d3 ON public.formation_translation USING btree (translatable_id);


--
-- Name: idx_f376f39b2c2ac5d3; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_f376f39b2c2ac5d3 ON public.experience_pro_translation USING btree (translatable_id);


--
-- Name: uniq_8d93d649989d9b62; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_8d93d649989d9b62 ON public."user" USING btree (slug);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON public."user" USING btree (email);


--
-- Name: uniq_identifier_email; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_identifier_email ON public.admin USING btree (email);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: app
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: user_translation fk_1d728cfa2c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.user_translation
    ADD CONSTRAINT fk_1d728cfa2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public."user"(id);


--
-- Name: outil fk_22627a3ea76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.outil
    ADD CONSTRAINT fk_22627a3ea76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: experience_uni_translation fk_2c533f8c2c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni_translation
    ADD CONSTRAINT fk_2c533f8c2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public.experience_uni(id);


--
-- Name: langage_translation fk_3fa504be2c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage_translation
    ADD CONSTRAINT fk_3fa504be2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public.langage(id);


--
-- Name: formation fk_404021bfa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation
    ADD CONSTRAINT fk_404021bfa76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: competence fk_94d4687fa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.competence
    ADD CONSTRAINT fk_94d4687fa76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: experience_pro fk_a52ce209a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro
    ADD CONSTRAINT fk_a52ce209a76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: experience_uni fk_acf3d88aa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_uni
    ADD CONSTRAINT fk_acf3d88aa76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: loisir_translation fk_b377a1182c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir_translation
    ADD CONSTRAINT fk_b377a1182c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public.loisir(id);


--
-- Name: langage fk_cc50ea26a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.langage
    ADD CONSTRAINT fk_cc50ea26a76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: loisir fk_cf3b2060a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.loisir
    ADD CONSTRAINT fk_cf3b2060a76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: formation_translation fk_eb9465b42c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.formation_translation
    ADD CONSTRAINT fk_eb9465b42c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public.formation(id);


--
-- Name: experience_pro_translation fk_f376f39b2c2ac5d3; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.experience_pro_translation
    ADD CONSTRAINT fk_f376f39b2c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES public.experience_pro(id);


--
-- PostgreSQL database dump complete
--

