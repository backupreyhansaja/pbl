--
-- PostgreSQL database dump
--

\restrict aW4HN1Md706riHhUsMWTCVP22MYu5iNCyVgbUMWXscNAemi2O4QNZhki9AnlUHA

-- Dumped from database version 15.14
-- Dumped by pg_dump version 15.14

-- Started on 2025-11-26 18:55:21

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 215 (class 1259 OID 17768)
-- Name: admin_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    email character varying(100) NOT NULL,
    full_name character varying(100) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.admin_users OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 17767)
-- Name: admin_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.admin_users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.admin_users_id_seq OWNER TO postgres;

--
-- TOC entry 3455 (class 0 OID 0)
-- Dependencies: 214
-- Name: admin_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.admin_users_id_seq OWNED BY public.admin_users.id;


--
-- TOC entry 231 (class 1259 OID 17857)
-- Name: berita; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.berita (
    id integer NOT NULL,
    judul character varying(255) NOT NULL,
    isi text NOT NULL,
    deskripsi text,
    gambar character varying(255),
    kategori character varying(100),
    tanggal date NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    uploaded_by integer
);


ALTER TABLE public.berita OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 17856)
-- Name: berita_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.berita_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.berita_id_seq OWNER TO postgres;

--
-- TOC entry 3456 (class 0 OID 0)
-- Dependencies: 230
-- Name: berita_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.berita_id_seq OWNED BY public.berita.id;


--
-- TOC entry 233 (class 1259 OID 17953)
-- Name: blueprint; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.blueprint (
    id integer NOT NULL,
    title character varying(150) NOT NULL,
    description text NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(50),
    urutan integer DEFAULT 0,
    uploaded_by integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.blueprint OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 17952)
-- Name: blueprint_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.blueprint_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.blueprint_id_seq OWNER TO postgres;

--
-- TOC entry 3457 (class 0 OID 0)
-- Dependencies: 232
-- Name: blueprint_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.blueprint_id_seq OWNED BY public.blueprint.id;


--
-- TOC entry 229 (class 1259 OID 17846)
-- Name: contact_messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact_messages (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    subject character varying(200),
    message text NOT NULL,
    is_read boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.contact_messages OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 17845)
-- Name: contact_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contact_messages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contact_messages_id_seq OWNER TO postgres;

--
-- TOC entry 3458 (class 0 OID 0)
-- Dependencies: 228
-- Name: contact_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contact_messages_id_seq OWNED BY public.contact_messages.id;


--
-- TOC entry 227 (class 1259 OID 17835)
-- Name: gallery; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gallery (
    id integer NOT NULL,
    title character varying(200) NOT NULL,
    description text,
    image character varying(255) NOT NULL,
    tanggal date,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.gallery OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 17834)
-- Name: gallery_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gallery_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gallery_id_seq OWNER TO postgres;

--
-- TOC entry 3459 (class 0 OID 0)
-- Dependencies: 226
-- Name: gallery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gallery_id_seq OWNED BY public.gallery.id;


--
-- TOC entry 225 (class 1259 OID 17824)
-- Name: mahasiswa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mahasiswa (
    id integer NOT NULL,
    nama character varying(100) NOT NULL,
    nim character varying(50) NOT NULL,
    program_studi character varying(100),
    email character varying(100),
    phone character varying(20),
    foto character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.mahasiswa OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 17823)
-- Name: mahasiswa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mahasiswa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mahasiswa_id_seq OWNER TO postgres;

--
-- TOC entry 3460 (class 0 OID 0)
-- Dependencies: 224
-- Name: mahasiswa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mahasiswa_id_seq OWNED BY public.mahasiswa.id;


--
-- TOC entry 235 (class 1259 OID 17970)
-- Name: scope; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.scope (
    id integer NOT NULL,
    title character varying(150) NOT NULL,
    description text NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(50),
    urutan integer DEFAULT 0,
    uploaded_by integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.scope OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 17969)
-- Name: scope_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.scope_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.scope_id_seq OWNER TO postgres;

--
-- TOC entry 3461 (class 0 OID 0)
-- Dependencies: 234
-- Name: scope_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.scope_id_seq OWNED BY public.scope.id;


--
-- TOC entry 219 (class 1259 OID 17793)
-- Name: sejarah; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sejarah (
    id integer NOT NULL,
    content text NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.sejarah OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 17792)
-- Name: sejarah_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sejarah_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sejarah_id_seq OWNER TO postgres;

--
-- TOC entry 3462 (class 0 OID 0)
-- Dependencies: 218
-- Name: sejarah_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sejarah_id_seq OWNED BY public.sejarah.id;


--
-- TOC entry 223 (class 1259 OID 17813)
-- Name: staff; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.staff (
    id integer NOT NULL,
    nama character varying(100) NOT NULL,
    nip character varying(50),
    jabatan character varying(100),
    email character varying(100),
    phone character varying(20),
    foto character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.staff OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 17812)
-- Name: staff_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.staff_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.staff_id_seq OWNER TO postgres;

--
-- TOC entry 3463 (class 0 OID 0)
-- Dependencies: 222
-- Name: staff_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.staff_id_seq OWNED BY public.staff.id;


--
-- TOC entry 221 (class 1259 OID 17803)
-- Name: struktur_organisasi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.struktur_organisasi (
    id integer NOT NULL,
    jabatan character varying(100) NOT NULL,
    nama character varying(100) NOT NULL,
    foto character varying(255),
    urutan integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.struktur_organisasi OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 17802)
-- Name: struktur_organisasi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.struktur_organisasi_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.struktur_organisasi_id_seq OWNER TO postgres;

--
-- TOC entry 3464 (class 0 OID 0)
-- Dependencies: 220
-- Name: struktur_organisasi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.struktur_organisasi_id_seq OWNED BY public.struktur_organisasi.id;


--
-- TOC entry 217 (class 1259 OID 17783)
-- Name: visi_misi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.visi_misi (
    id integer NOT NULL,
    visi text NOT NULL,
    misi text NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.visi_misi OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 17782)
-- Name: visi_misi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.visi_misi_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.visi_misi_id_seq OWNER TO postgres;

--
-- TOC entry 3465 (class 0 OID 0)
-- Dependencies: 216
-- Name: visi_misi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.visi_misi_id_seq OWNED BY public.visi_misi.id;


--
-- TOC entry 3223 (class 2604 OID 17919)
-- Name: admin_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_users ALTER COLUMN id SET DEFAULT nextval('public.admin_users_id_seq'::regclass);


--
-- TOC entry 3246 (class 2604 OID 17920)
-- Name: berita id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita ALTER COLUMN id SET DEFAULT nextval('public.berita_id_seq'::regclass);


--
-- TOC entry 3249 (class 2604 OID 17956)
-- Name: blueprint id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blueprint ALTER COLUMN id SET DEFAULT nextval('public.blueprint_id_seq'::regclass);


--
-- TOC entry 3243 (class 2604 OID 17921)
-- Name: contact_messages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_messages ALTER COLUMN id SET DEFAULT nextval('public.contact_messages_id_seq'::regclass);


--
-- TOC entry 3240 (class 2604 OID 17922)
-- Name: gallery id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery ALTER COLUMN id SET DEFAULT nextval('public.gallery_id_seq'::regclass);


--
-- TOC entry 3237 (class 2604 OID 17923)
-- Name: mahasiswa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa ALTER COLUMN id SET DEFAULT nextval('public.mahasiswa_id_seq'::regclass);


--
-- TOC entry 3253 (class 2604 OID 17973)
-- Name: scope id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scope ALTER COLUMN id SET DEFAULT nextval('public.scope_id_seq'::regclass);


--
-- TOC entry 3228 (class 2604 OID 17924)
-- Name: sejarah id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sejarah ALTER COLUMN id SET DEFAULT nextval('public.sejarah_id_seq'::regclass);


--
-- TOC entry 3234 (class 2604 OID 17925)
-- Name: staff id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff ALTER COLUMN id SET DEFAULT nextval('public.staff_id_seq'::regclass);


--
-- TOC entry 3230 (class 2604 OID 17926)
-- Name: struktur_organisasi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.struktur_organisasi ALTER COLUMN id SET DEFAULT nextval('public.struktur_organisasi_id_seq'::regclass);


--
-- TOC entry 3226 (class 2604 OID 17927)
-- Name: visi_misi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visi_misi ALTER COLUMN id SET DEFAULT nextval('public.visi_misi_id_seq'::regclass);


--
-- TOC entry 3429 (class 0 OID 17768)
-- Dependencies: 215
-- Data for Name: admin_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.admin_users (id, username, password, email, full_name, created_at, updated_at) FROM stdin;
1	admin	0192023a7bbd73250516f069df18b500	admin@lab.ac.id	Administrator	2025-11-13 07:28:03.139366	2025-11-13 07:28:03.139366
\.


--
-- TOC entry 3445 (class 0 OID 17857)
-- Dependencies: 231
-- Data for Name: berita; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.berita (id, judul, isi, deskripsi, gambar, kategori, tanggal, created_at, updated_at, uploaded_by) FROM stdin;
16	sad	<p>ini <strong>Bold </strong>ini <em>italic</em><span class="ql-cursor">﻿</span></p>	ds		Pengumuman	2025-11-25	2025-11-25 23:27:36.763494	2025-11-25 23:27:36.763494	1
\.


--
-- TOC entry 3447 (class 0 OID 17953)
-- Dependencies: 233
-- Data for Name: blueprint; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.blueprint (id, title, description, icon, color, urutan, uploaded_by, created_at, updated_at) FROM stdin;
1	Education Technology	Automated assistance, auto-grading, software testing, gamification, simulations, and learning methodology	fa-solid fa-graduation-cap	#A66CFF	1	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
2	Smart Farming	IoT, sensors, data analysis, computer vision, and artificial intelligence	fa-solid fa-leaf	#2ecc71	2	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
3	Information Security	SIEM and EEG-based data analysis for threat detection and cognitive-based security	fa-solid fa-shield-halved	#3498db	3	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
\.


--
-- TOC entry 3443 (class 0 OID 17846)
-- Dependencies: 229
-- Data for Name: contact_messages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contact_messages (id, name, email, subject, message, is_read, created_at) FROM stdin;
2	Bayu	aditryabayuramadhani@gmail.com	peminjaman lab	saya ingin meminjam lab	f	2025-11-13 09:38:16.961248
\.


--
-- TOC entry 3441 (class 0 OID 17835)
-- Dependencies: 227
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gallery (id, title, description, image, tanggal, created_at, updated_at) FROM stdin;
3	tugas 2	ini tugas	uploads/gallery/1763002058_qbit.png	2025-11-13	2025-11-13 09:47:38.653112	2025-11-13 09:47:38.653112
4	tugas 3		uploads/gallery/1763002073_qbit.png	2025-11-13	2025-11-13 09:47:53.630077	2025-11-13 09:47:53.630077
\.


--
-- TOC entry 3439 (class 0 OID 17824)
-- Dependencies: 225
-- Data for Name: mahasiswa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mahasiswa (id, nama, nim, program_studi, email, phone, foto, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3449 (class 0 OID 17970)
-- Dependencies: 235
-- Data for Name: scope; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.scope (id, title, description, icon, color, urutan, uploaded_by, created_at, updated_at) FROM stdin;
1	Information System & Automation	Building information systems to support organizational management, business, health, and education.	fa-solid fa-display	#6C5CE7	1	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
3	Application Development	Designing and building desktop, web, and mobile applications for industrial and academic needs.	fa-solid fa-mobile-screen	#00B894	3	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
4	Internet of Things & Applied Technologies	Integrating hardware and software to produce intelligent solutions.	fa-solid fa-microchip	#0984E3	4	1	2025-11-26 08:39:54.023369	2025-11-26 08:39:54.023369
2	Artificial Intelligence	Analyze data, create machine learning models, and develop intelligent systems for decision-making.	fa-solid fa-book	#E84393	2	1	2025-11-26 08:39:54.023369	2025-11-26 08:46:58.301218
\.


--
-- TOC entry 3433 (class 0 OID 17793)
-- Dependencies: 219
-- Data for Name: sejarah; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sejarah (id, content, updated_at) FROM stdin;
1	Laboratorium Komputer didirikan pada tahun 2010 sebagai bagian dari upaya peningkatan kualitas pendidikan di bidang teknologi informasi. Sejak awal berdirinya, laboratorium ini telah dilengkapi dengan berbagai fasilitas modern untuk mendukung kegiatan praktikum dan penelitian mahasiswa.\r\n\r\nDalam perjalanannya, laboratorium terus berkembang dan melakukan berbagai inovasi baik dalam hal fasilitas maupun program-program unggulan. Hingga saat ini, laboratorium telah menghasilkan berbagai penelitian dan karya inovasi yang bermanfaat bagi masyarakat luas.	2025-11-13 07:28:03.143451
\.


--
-- TOC entry 3437 (class 0 OID 17813)
-- Dependencies: 223
-- Data for Name: staff; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.staff (id, nama, nip, jabatan, email, phone, foto, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3435 (class 0 OID 17803)
-- Dependencies: 221
-- Data for Name: struktur_organisasi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.struktur_organisasi (id, jabatan, nama, foto, urutan, created_at, updated_at) FROM stdin;
1	Ketua Laboratorium	Raihan	uploads/struktur/1763001714_qbit.png	0	2025-11-13 09:41:54.372434	2025-11-13 09:42:57.171064
\.


--
-- TOC entry 3431 (class 0 OID 17783)
-- Dependencies: 217
-- Data for Name: visi_misi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.visi_misi (id, visi, misi, updated_at) FROM stdin;
1	Menjadi laboratorium unggulan yang menghasilkan penelitian dan inovasi berkualitas tinggi dalam bidang teknologi informasi.	1. Menyediakan fasilitas laboratorium yang modern dan lengkap\r\n2. Mendukung kegiatan penelitian mahasiswa dan dosen\r\n3. Mengembangkan inovasi teknologi yang bermanfaat bagi masyarakat\r\n4. Menjalin kerjasama dengan industri dan institusi lain\r\n5. Meningkatkan kompetensi SDM di bidang teknologi informasi	2025-11-13 07:28:03.142486
\.


--
-- TOC entry 3466 (class 0 OID 0)
-- Dependencies: 214
-- Name: admin_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.admin_users_id_seq', 1, true);


--
-- TOC entry 3467 (class 0 OID 0)
-- Dependencies: 230
-- Name: berita_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.berita_id_seq', 16, true);


--
-- TOC entry 3468 (class 0 OID 0)
-- Dependencies: 232
-- Name: blueprint_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.blueprint_id_seq', 3, true);


--
-- TOC entry 3469 (class 0 OID 0)
-- Dependencies: 228
-- Name: contact_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contact_messages_id_seq', 2, true);


--
-- TOC entry 3470 (class 0 OID 0)
-- Dependencies: 226
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gallery_id_seq', 4, true);


--
-- TOC entry 3471 (class 0 OID 0)
-- Dependencies: 224
-- Name: mahasiswa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mahasiswa_id_seq', 1, false);


--
-- TOC entry 3472 (class 0 OID 0)
-- Dependencies: 234
-- Name: scope_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.scope_id_seq', 4, true);


--
-- TOC entry 3473 (class 0 OID 0)
-- Dependencies: 218
-- Name: sejarah_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sejarah_id_seq', 1, true);


--
-- TOC entry 3474 (class 0 OID 0)
-- Dependencies: 222
-- Name: staff_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.staff_id_seq', 1, false);


--
-- TOC entry 3475 (class 0 OID 0)
-- Dependencies: 220
-- Name: struktur_organisasi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.struktur_organisasi_id_seq', 1, true);


--
-- TOC entry 3476 (class 0 OID 0)
-- Dependencies: 216
-- Name: visi_misi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visi_misi_id_seq', 1, true);


--
-- TOC entry 3258 (class 2606 OID 17781)
-- Name: admin_users admin_users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_users
    ADD CONSTRAINT admin_users_email_key UNIQUE (email);


--
-- TOC entry 3260 (class 2606 OID 17777)
-- Name: admin_users admin_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_users
    ADD CONSTRAINT admin_users_pkey PRIMARY KEY (id);


--
-- TOC entry 3262 (class 2606 OID 17779)
-- Name: admin_users admin_users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_users
    ADD CONSTRAINT admin_users_username_key UNIQUE (username);


--
-- TOC entry 3278 (class 2606 OID 17866)
-- Name: berita berita_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita
    ADD CONSTRAINT berita_pkey PRIMARY KEY (id);


--
-- TOC entry 3280 (class 2606 OID 17963)
-- Name: blueprint blueprint_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blueprint
    ADD CONSTRAINT blueprint_pkey PRIMARY KEY (id);


--
-- TOC entry 3276 (class 2606 OID 17855)
-- Name: contact_messages contact_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_messages
    ADD CONSTRAINT contact_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 3274 (class 2606 OID 17844)
-- Name: gallery gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- TOC entry 3272 (class 2606 OID 17833)
-- Name: mahasiswa mahasiswa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_pkey PRIMARY KEY (id);


--
-- TOC entry 3282 (class 2606 OID 17980)
-- Name: scope scope_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scope
    ADD CONSTRAINT scope_pkey PRIMARY KEY (id);


--
-- TOC entry 3266 (class 2606 OID 17801)
-- Name: sejarah sejarah_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sejarah
    ADD CONSTRAINT sejarah_pkey PRIMARY KEY (id);


--
-- TOC entry 3270 (class 2606 OID 17822)
-- Name: staff staff_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_pkey PRIMARY KEY (id);


--
-- TOC entry 3268 (class 2606 OID 17811)
-- Name: struktur_organisasi struktur_organisasi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.struktur_organisasi
    ADD CONSTRAINT struktur_organisasi_pkey PRIMARY KEY (id);


--
-- TOC entry 3264 (class 2606 OID 17791)
-- Name: visi_misi visi_misi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visi_misi
    ADD CONSTRAINT visi_misi_pkey PRIMARY KEY (id);


--
-- TOC entry 3283 (class 2606 OID 17867)
-- Name: berita berita_uploaded_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita
    ADD CONSTRAINT berita_uploaded_by_fkey FOREIGN KEY (uploaded_by) REFERENCES public.admin_users(id);


--
-- TOC entry 3284 (class 2606 OID 17964)
-- Name: blueprint blueprint_uploaded_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blueprint
    ADD CONSTRAINT blueprint_uploaded_by_fkey FOREIGN KEY (uploaded_by) REFERENCES public.admin_users(id);


--
-- TOC entry 3285 (class 2606 OID 17981)
-- Name: scope scope_uploaded_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scope
    ADD CONSTRAINT scope_uploaded_by_fkey FOREIGN KEY (uploaded_by) REFERENCES public.admin_users(id);


-- Completed on 2025-11-26 18:55:21

--
-- PostgreSQL database dump complete
--

\unrestrict aW4HN1Md706riHhUsMWTCVP22MYu5iNCyVgbUMWXscNAemi2O4QNZhki9AnlUHA

