-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/11/2024 às 04:17
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `busca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id_avaliacao` int(11) NOT NULL,
  `fk_usuario_id_usuario` int(11) NOT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_profissional_id_profissional` int(11) NOT NULL,
  `estrelas_avaliacao` int(1) NOT NULL CHECK (`estrelas_avaliacao` >= 0 and `estrelas_avaliacao` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id_avaliacao`, `fk_usuario_id_usuario`, `data_avaliacao`, `fk_profissional_id_profissional`, `estrelas_avaliacao`) VALUES
(23, 2, '2024-11-24 23:02:20', 20, 4),
(24, 2, '2024-11-19 20:16:54', 15, 4),
(25, 2, '2024-11-19 21:02:09', 23, 4),
(26, 2, '2024-11-19 20:35:57', 21, 4),
(27, 2, '2024-11-19 20:39:59', 13, 3),
(28, 2, '2024-11-19 21:26:24', 5, 4),
(29, 2, '2024-11-20 14:08:36', 11, 4),
(30, 2, '2024-11-24 03:36:14', 9, 3),
(31, 2, '2024-11-20 15:08:57', 8, 4),
(32, 2, '2024-11-20 15:14:10', 19, 4),
(33, 2, '2024-11-20 16:52:26', 7, 4),
(34, 2, '2024-11-20 17:02:21', 6, 4),
(35, 2, '2024-11-20 19:37:42', 17, 3),
(36, 2, '2024-11-20 22:02:48', 10, 4),
(37, 2, '2024-11-20 22:03:36', 14, 4),
(38, 2, '2024-11-21 15:27:15', 16, 4),
(39, 2, '2024-11-26 15:23:54', 24, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `departamentos`
--

CREATE TABLE `departamentos` (
  `id_area` int(11) NOT NULL,
  `nome_area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `departamentos`
--

INSERT INTO `departamentos` (`id_area`, `nome_area`) VALUES
(1, 'Cuidados Pessoais'),
(2, 'Residencial'),
(3, 'Cuidado Automotivo'),
(4, 'Educação e Aulas Particulares'),
(5, 'Saúde e Bem-Estar'),
(6, 'Tecnologia e Serviços Digitais'),
(7, 'Eventos e Festas'),
(8, 'Serviços Gerais');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_perfil`
--

CREATE TABLE `fotos_perfil` (
  `id_foto_perfil` int(11) NOT NULL,
  `caminho_foto_perfil` varchar(255) DEFAULT NULL,
  `fk_profissional_id_profissional` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fotos_perfil`
--

INSERT INTO `fotos_perfil` (`id_foto_perfil`, `caminho_foto_perfil`, `fk_profissional_id_profissional`) VALUES
(1, 'uploads/fotos_perfil/673f3ec4a7d8d.jpg', 5),
(2, 'uploads/fotos_perfil/673f3ee805401.jpg', 5),
(3, 'uploads/fotos_perfil/673f3ef4d5891.jpg', 5),
(4, 'uploads/fotos_perfil/673f3f351be38.jpg', 5),
(5, 'uploads/fotos_perfil/673f3f4f30ba8.jpg', 5),
(6, 'uploads/fotos_perfil/673f3f720ffd2.jpg', 5),
(7, 'uploads/fotos_perfil/673f3f75d6e08.jpg', 5),
(8, 'uploads/fotos_perfil/673f3fc464554.jpg', 5),
(9, 'uploads/fotos_perfil/673f3fccf0311.jpg', 5),
(10, 'uploads/fotos_perfil/673f3fd260c01.jpg', 5),
(11, 'uploads/fotos_perfil/673f3fdcb48f9.jpg', 5),
(12, 'uploads/fotos_perfil/673f4d63d370f.jpg', 5),
(13, 'uploads/fotos_perfil/673f4d67024ab.jpg', 5),
(14, 'uploads/fotos_perfil/6741214d71f82.jpg', 24),
(15, 'uploads/fotos_perfil/6741215008336.jpg', 24),
(16, 'uploads/fotos_perfil/67415529b3568.jpg', 29),
(17, 'uploads/fotos_perfil/6741553092bca.jpg', 29),
(18, 'uploads/fotos_perfil/67415545aadcf.jpg', 29),
(19, 'uploads/fotos_perfil/6741554f725e7.jpg', 29),
(20, 'uploads/fotos_perfil/674155795eaba.jpg', 29),
(21, 'uploads/fotos_perfil/674155876dcaa.jpg', 29),
(22, 'uploads/fotos_perfil/674155a2324ed.jpg', 29),
(23, 'uploads/fotos_perfil/674155b038f64.jpg', 29),
(24, 'uploads/fotos_perfil/674155fc23d50.jpg', 29),
(25, 'uploads/fotos_perfil/674156003d1e9.jpg', 29),
(26, 'uploads/fotos_perfil/6741598533c7c.jpg', 29),
(27, 'uploads/fotos_perfil/674159892cc93.jpg', 29),
(28, 'uploads/fotos_perfil/67421291748f8.png', NULL),
(29, 'uploads/fotos_perfil/6745e5f876ae8.jpeg', 31),
(30, 'uploads/fotos_perfil/6746375bbc949.png', 36);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_profissionais`
--

CREATE TABLE `fotos_profissionais` (
  `id_foto` int(11) NOT NULL,
  `caminho_foto` varchar(255) DEFAULT NULL,
  `fk_profissional_id_profissional` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fotos_profissionais`
--

INSERT INTO `fotos_profissionais` (`id_foto`, `caminho_foto`, `fk_profissional_id_profissional`) VALUES
(10, 'uploads/fotos_profissionais/673e4899c9348.png', 5),
(15, 'uploads/fotos_profissionais/673f4e9782d9d.png', 5),
(17, 'uploads/fotos_profissionais/6745e60f4c243.png', 31),
(18, 'uploads/fotos_profissionais/6745f034ec217.jpeg', 35),
(19, 'uploads/fotos_profissionais/674637efebad7.png', 36),
(20, 'uploads/fotos_profissionais/674637f5d480a.png', 36);

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissional`
--

CREATE TABLE `profissional` (
  `id_profissional` int(11) NOT NULL,
  `nome_profissional` varchar(50) NOT NULL,
  `senha_profissional` varchar(255) NOT NULL,
  `descricao_profissional` text DEFAULT NULL,
  `email_profissional` varchar(50) NOT NULL,
  `tel_profissional` varchar(11) NOT NULL,
  `ranking_profissional` int(11) NOT NULL,
  `fk_departamentos_id_area` int(11) NOT NULL,
  `fk_profissoes_id_profissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `profissional`
--

INSERT INTO `profissional` (`id_profissional`, `nome_profissional`, `senha_profissional`, `descricao_profissional`, `email_profissional`, `tel_profissional`, `ranking_profissional`, `fk_departamentos_id_area`, `fk_profissoes_id_profissao`) VALUES
(5, 'Ma', '$2y$10$07XqXBQglq4Sfg0uBGzgnulbWxlmZ0xZDLNTUP/T94yplqjSM.i..', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum Maecenas ipsum velit,', 'ma@gmail.com', '19998414123', 0, 1, 1),
(6, 'rafa', '$2y$10$75mO/bepgvrN0vEOOoB5B.4BrIoeAsrWMa7KccWHQ6XVUuGWMEkj6', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum', 'rafa@gmail.com', '19999098889', 0, 3, 7),
(7, 'Jose', '$2y$10$tyiu6vwmivn9J/dymecMnO0KA3lCHuu8apuFIw4ozUFazTty7g8ny', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'jose@gmaisl', '12312321322', 0, 3, 7),
(8, 'mariana', '$2y$10$ecaeyJmMi.zNZhvLVDSTI.zDNuPzfCOJARRi4m62bDrUA8LqNRAjm', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'marianam@gmails', '1293923923', 0, 3, 7),
(9, 'cleide', '$2y$10$soT9NwymS.vcprBd2HhzPOSjtXjW67SiLcAxVxev4kfPG6QCmlYXm', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'cliede@gmais', '12321312312', 0, 3, 7),
(10, 'marina lima', '$2y$10$4K7ckhGovbCh.eLlMvZOq.yjZC5qVXzj8ddNWzM8LHklNo..JZ6s2', 'Lorem ipsum dolor sit amet. 33 quas quam sit veritatis alias eos facere corporis ea voluptas ipsum sit molestias dolor. Et sint impedit est voluptatem commodi qui perferendis fugit? Ab eius accusantium qui sint dolorem id voluptas quia aut autem voluptas. Qui aliquid galisum et vitae quia et omnis deserunt qui repellendus natus. </p><p>Et sint animi qui eius ullam non ratione corporis At voluptas quasi. Sit eaque deleniti aut ipsum maxime cum magni quam. Quo enim voluptas At earum dolor in praesentium necessitatibus. </p><p>Non ducimus deleniti non animi reiciendis non dolore nemo quo sunt magnam aut voluptatem tempora sit sapiente natus! Sit repudiandae ducimus qui magni enim sed quos itaque et voluptas voluptate qui pariatur nulla. Et perferendis repellendus non vitae odit quo debitis sequi? Ex veritatis nihil et dolorem repellat est vero illum? \n', 'lima@marina', '19712334512', 0, 1, 4),
(11, 'Ana Borges ', '$2y$10$siKF3bnA3v7oEWGXoaOBhu0L96AH5vWk5VilP9unYMrL7x/zr7h/y', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'ana@outlook.com.br', '19999098884', 0, 2, 6),
(12, 'VITOR EDUARDO DE OLIVEIRA', '$2y$10$Fo3xgBCin4WWizPBSRgfqOP4UYJ599/6ftzfH9cD9ZvL75g9bwm3m', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'emailparavitor@gmail.com', '19997535726', 0, 1, 4),
(13, 'Maria Vitoria Suzarth', '$2y$10$frqqeYohURQZov8HFGD9uuAkhLas.I9GXXeU7rs7vteWbUJsKAFG.', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'maria.suzarth@hotmail.com', '19996595529', 0, 1, 4),
(14, 'Victor Gabriel Borges Lima 5', '$2y$10$jTzssrePnoCRQQ1NwbgzHuAGZp0Qdkd9oA02Afb2mVOnRx2rb8V0O', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'vi@gmail.com', '19996387167', 0, 1, 4),
(15, 'Cintia', '$2y$10$vF.CosrztsBCgP1gPODiI.JBC0YZaGwiAg4EqtS5BFeQUSuJH3f0W', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'cintia@gmail', '12313232323', 0, 1, 1),
(16, 'Victor Gabriel Borges', '$2y$10$zg32zFmEXythkus8pokdJOGWysRtohdUInyekGkxe5IJt.86SW4Ja', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'vic@gmail.com', '19996387167', 0, 2, 6),
(17, 'ana lima', '$2y$10$CCsCHGeamJEYrZdVOGnuMuOHKsaygpvbBMixkgteC1NNZbsmkonIa', 'Maecenas ipsum velit, consectetuer eu, lobortis ut, dictum at, dui. In rutrum. Sed ac dolor sit amet purus malesuada congue. In laoreet, magna id viverra tincidunt, sem odio bibendum justo, vel imperdiet sapien wisi sed libero. Suspendisse sagittis ultrices augue. Mauris metus.ss', 'lima@gmail.com', '19998414123', 0, 2, 6),
(18, 'Ivo Onofre', '$2y$10$25PGdnXXw6xQXoBRLHMTZOtcwkIZZbhDWvqxl6v5OQj5HbN8eTbpq', 'Ivo OnofreIvo OnofreIvo OnofreIvo OnofreIvo OnofreIvo OnofreIvo OnofreIvo OnofreIvo OnofreIvo Onofre', 'onofre@gmail.com', '19998414123', 0, 2, 2),
(19, 'talita', '$2y$10$63j6U5SzjyEFLKOwq9maQ.yv6xTDFpnpXdU9FB72ba7k3V7a16rAW', 'Caso o problema persista, por favor, compartilhe mais detalhes para que eu possa ajudar a diagnosticar melhor. Caso o problema persista, por favor, compartilhe mais detalhes para que eu possa ajudar a diagnosticar melhor.', 'talita@gmail.com', '19999998884', 0, 1, 1),
(20, 'descia', '$2y$10$AAD3yQLLQIfkw/hEhDgM2.v4gLI2OgH.9ZGZ8x89lcy6u12zfRe8m', 'desciadesciadesciadesciadesciadesciadesciadesciadesciadesciadesciadesciadesciadesciadescia', 'descia@gmail.com', '19555555555', 0, 2, 5),
(21, 'lima', '$2y$10$z1s/HdmvnXpgVlL/nvbnd.bZfJQkzpqKZvvLZI1VkoBiWnk7Amzq2', 'limalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalimalima', 'correa@gmail.com', '12345647897', 0, 1, 4),
(22, 'luiza', '$2y$10$ATjYyMElI2Gyo9V3AXK4KeXpwtgNg5eghs1XjcuIN.zqGOLYFYUAO', 'luizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluizaluiza', 'lu2@gmail.com', '19998414123', 0, 2, 2),
(23, 'genilda', '$2y$10$MyFT1g7PYr6rmn6kClXBuudoOCbPeIa/5XSaTcPnHAF.RrUQvCXjO', 'genildagenildagenildagenildagenildagenildagenildagenildagenildagenildagenildagenilda', 'gege@gmail.com', '12345678955', 0, 1, 4),
(24, 'jucicleia', '$2y$10$R4vgBR1k7jU4ugr0Go5dAeOdgIiktk9Bc/2Q0RFWWEYWA12UjzmEO', 'jucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleiajucicleia', 'juci@gmail.com', '19998414123', 0, 1, 4),
(25, 'paola', '$2y$10$80Hn1BAG6Fu/1qUYe42JLOB97sQadJtHjE6f.49g5Ado5PgolFloO', 'paolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaolapaola', 'paola@gmail.com', '12345678945', 0, 2, 3),
(26, 'tina', '$2y$10$WNVm3kb4SOiRzqP7MHJ8euTQdSms1zkSWnUhzIuzeR0S4mhzf9gBy', 'tinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatinatina', 'tina@gmail.com', '12345678978', 0, 2, 5),
(29, 'Samantha Lambertini', '$2y$10$mcfulidluR.2nrvig7oGOeBML98qU.pK.K2J19vR2r4mhhG8O0ZYO', 'Samantha LambertiniSamantha LambertiniSamantha Samantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniLambertiniSamantha Samantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniLambertiniSamantha LambertiniSamantha LambertiniSamantha LambertiniSamantha Lambertini', 'samantinha@gmail.com', '12345678945', 0, 2, 3),
(31, 'Carol Lopes', '$2y$10$TVoKbYdFNjDkHfNs8tRmAOFsq8ZY0NYnfNCieS1L372TeAnqpZ2y2', 'Carol Lopes Carol Lopes Carol Lopes Carol Lopes Carol Lopes Carol Lopes Carol Lopes Carol Lopes', 'carol@gmail.com', '19994545454', 0, 1, 10),
(32, 'Kalica ', '$2y$10$XZO8RDnukctb8T6yu.BMeu.jFONy1Xym/MtUL6R5ik/.LvYPR08P.', 'Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica Kalica ', 'kalica@gmail.com', '12345678945', 0, 1, 11),
(33, 'Cleiton Pereira', '$2y$10$.TA4TrKdImNqYV5FQY2o7uLexwYNzAWz.mrsW8ULHTr4PStRQs0A.', 'gagagbdjsjbdeewihodcçhdsnjcipdauduigdusidgcuigsducgasdoiidcguigdigiudgoidhdcuccklz', 'Cleitin@gmail.com', '19966554422', 0, 6, 31),
(34, 'MAVI', '$2y$10$t/cRxjKCNzEtcTTTgR4s0.vJUyebc.tI8.70YFO0dV6p9lW00bsrq', 'DESCRIÇÃO PROFISSIONAL TESTEZINHOOOOOOOOOOO DESCRIÇÃO PROFISSIONAL TESTEZINHOOOOOOOOOOO', 'mavi@email.com', '19996582255', 0, 1, 12),
(35, 'Jiovanes ', '$2y$10$BpyOhNdNGzWPB1Qfv2YE6.v0SplZVi.CvO8k6bCjcavFyMWmUbpYi', 'É us guri jdhfndndbdjdhdhfjfjjfjdjdjdjdjdjjdjdjdjdjdjdjdhdhdhdhdndbdbdbbdbdbdbdbdbdhdhdhdn', 'glopesribeiro04@gmail.com', '19989994437', 0, 7, 35),
(36, 'Rafaela Santos', '$2y$10$I0NQhUe5NYDPaTgVtQDGPe5L3EQplBQUhBClpa6FROhjHEAFWIQu.', 'Sou desenvolvedora web, com 2 anos de experiência em PHP, HTML e CSS, criando sites funcionais e visualmente atraentes. Também utilizo o Canva para desenvolver layouts e elementos gráficos, unindo design e tecnologia para entregar soluções completas e personalizadas.', 'rafasantos@gmail.com', '19996422951', 0, 6, 31);

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissoes`
--

CREATE TABLE `profissoes` (
  `id_profissao` int(11) NOT NULL,
  `nome_profissao` varchar(60) NOT NULL,
  `fk_departamentos_id_area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `profissoes`
--

INSERT INTO `profissoes` (`id_profissao`, `nome_profissao`, `fk_departamentos_id_area`) VALUES
(1, 'Manicure', 1),
(2, 'Pedreiro', 2),
(3, 'Pintor', 2),
(4, 'Massagista', 1),
(5, 'Calheiro', 2),
(6, 'Jardineiro', 2),
(7, 'Borracheiro', 3),
(8, 'Tatuador', 1),
(9, 'Eletriscista Automotivo', 3),
(10, 'Barbeiro(a)', 1),
(11, 'Maquiador(a)', 1),
(12, 'Esteticista', 1),
(13, 'Massoterapeuta', 1),
(14, 'Diarista', 2),
(15, 'Dedetizador', 2),
(18, 'Lavador de carros', 3),
(19, 'Mecânico', 3),
(20, 'Funileiro', 3),
(21, 'Professor de idiomas', 4),
(22, 'Professor de reforço escolar', 4),
(23, 'Instrutor de música', 4),
(24, 'Professor de desenho', 4),
(25, 'Personal Trainer', 5),
(26, 'Fisioterapeuta', 5),
(27, 'Nutricionista', 5),
(28, 'Psicólogo', 4),
(29, 'Enfermeiro', 5),
(30, 'Técnico em informática', 6),
(31, 'Desenvolvedor web', 6),
(32, 'Designer gráfico', 6),
(33, 'Decorador', 7),
(34, 'Fotógrafo', 7),
(35, 'DJ', 7),
(36, 'Locação de brinquedos infláveis', 7),
(41, 'Advogado', 8),
(42, 'Contador', 8),
(43, 'Motorista particular', 8),
(44, 'Passeador de cães', 8),
(51, 'Cuidador de idosos', 5),
(52, 'Arquiteto', 2),
(53, 'Costureira', 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tokens_redefinicao`
--

CREATE TABLE `tokens_redefinicao` (
  `id_redefinicao` int(11) NOT NULL,
  `email_redefinicao` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email_usuario` varchar(255) NOT NULL,
  `nome_usuario` varchar(100) NOT NULL,
  `senha_usuario` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email_usuario`, `nome_usuario`, `senha_usuario`, `criado_em`, `atualizado_em`) VALUES
(2, 'juris@gmail.com', 'juris', '$2y$10$XGMFSck4UwbiIoyJgTDjgeVBSw/CKXL6Ku7DnolxqEsjQ2KQ9uDtu', '2024-11-19 17:05:35', '2024-11-19 17:05:35'),
(3, 'keyla@gmail', 'keyla', '$2y$10$N.G46ZhJs2u0WnWhC9cuPOIhztIqach3z53Sm03PfVKbAZrDjxz12', '2024-11-23 00:25:40', '2024-11-23 00:25:40'),
(4, 'marina.borgesc@hotmail.com', 'Marina ', '123456', '2024-11-26 02:59:02', '2024-11-26 02:59:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id_avaliacao`),
  ADD KEY `fk_profissional_id_profissional` (`fk_profissional_id_profissional`),
  ADD KEY `fk_avaliacao_usuario` (`fk_usuario_id_usuario`);

--
-- Índices de tabela `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_area`);

--
-- Índices de tabela `fotos_perfil`
--
ALTER TABLE `fotos_perfil`
  ADD PRIMARY KEY (`id_foto_perfil`),
  ADD KEY `fk_profissional_id_profissional_idx` (`fk_profissional_id_profissional`);

--
-- Índices de tabela `fotos_profissionais`
--
ALTER TABLE `fotos_profissionais`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `fk_profissional` (`fk_profissional_id_profissional`);

--
-- Índices de tabela `profissional`
--
ALTER TABLE `profissional`
  ADD PRIMARY KEY (`id_profissional`),
  ADD KEY `FK_profissional_2` (`fk_departamentos_id_area`),
  ADD KEY `FK_profissional_3` (`fk_profissoes_id_profissao`);

--
-- Índices de tabela `profissoes`
--
ALTER TABLE `profissoes`
  ADD PRIMARY KEY (`id_profissao`),
  ADD KEY `FK_profissoes_2` (`fk_departamentos_id_area`);

--
-- Índices de tabela `tokens_redefinicao`
--
ALTER TABLE `tokens_redefinicao`
  ADD PRIMARY KEY (`id_redefinicao`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `fotos_perfil`
--
ALTER TABLE `fotos_perfil`
  MODIFY `id_foto_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `fotos_profissionais`
--
ALTER TABLE `fotos_profissionais`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `profissional`
--
ALTER TABLE `profissional`
  MODIFY `id_profissional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `profissoes`
--
ALTER TABLE `profissoes`
  MODIFY `id_profissao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `tokens_redefinicao`
--
ALTER TABLE `tokens_redefinicao`
  MODIFY `id_redefinicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`fk_profissional_id_profissional`) REFERENCES `profissional` (`id_profissional`),
  ADD CONSTRAINT `fk_avaliacao_usuario` FOREIGN KEY (`fk_usuario_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `fotos_perfil`
--
ALTER TABLE `fotos_perfil`
  ADD CONSTRAINT `fk_profissional_id_profissional` FOREIGN KEY (`fk_profissional_id_profissional`) REFERENCES `profissional` (`id_profissional`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `fotos_profissionais`
--
ALTER TABLE `fotos_profissionais`
  ADD CONSTRAINT `fk_profissional` FOREIGN KEY (`fk_profissional_id_profissional`) REFERENCES `profissional` (`id_profissional`),
  ADD CONSTRAINT `fotos_profissionais_ibfk_1` FOREIGN KEY (`fk_profissional_id_profissional`) REFERENCES `profissional` (`id_profissional`) ON DELETE CASCADE;

--
-- Restrições para tabelas `profissional`
--
ALTER TABLE `profissional`
  ADD CONSTRAINT `FK_profissional_2` FOREIGN KEY (`fk_departamentos_id_area`) REFERENCES `departamentos` (`id_area`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_profissional_3` FOREIGN KEY (`fk_profissoes_id_profissao`) REFERENCES `profissoes` (`id_profissao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `profissoes`
--
ALTER TABLE `profissoes`
  ADD CONSTRAINT `FK_profissoes_2` FOREIGN KEY (`fk_departamentos_id_area`) REFERENCES `departamentos` (`id_area`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
