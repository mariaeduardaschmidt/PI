<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de Doações</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php"><img src="logo.png" alt="Início"></a>
    </div>
    <nav>
      <ul>
        <li><a href="index1.php">Cadastrar Doação</a></li>
        <li><a href="index2.php">Relatórios</a></li>
        <li><a href="index3.php">Perfil</a></li>
        <li><a href="index4.php">Login</a></li>
      </ul>
    </nav>
  </header>

  <section class="destaque">
    <h2><span>Doe hoje e faça parte da mudança!</span></h2><br><br>
    <h3 id="frase"></h3><br>
    <h5 id="autor"></h5>
    <div class="card-container">
      <div class="card" data-price="como doar" onclick="expandCard(event, this)">
        <img src="imagem1.png" alt="Como doar">
        <h2>Como doar</h2><br>
        <p>Para fazer uma doação, basta ir até a secretaria da FMP e ........</p>
      </div>
      <div class="card" data-price="importância das doações" onclick="expandCard(event, this)">
        <img src="imagem2.png" alt="Importância das doações">
        <h2>Importância das doações</h2><br>
        <p>Fazer doações para a faculdade traz uma série de impactos significativos e contribui diretamente para o crescimento e a sustentabilidade de diversas iniciativas acadêmicas e sociais. Como auxiliar nos equipamentos utilizados na brinquedoteca (lugar para que as mães/responsáveis possam deixar as crianças enquanto estudam), auxiliar mulheres que passam pelo tratamento de câncer (através das doações de mechas de cabelo e lenços), doação de agasalhos para que mais pessoas possam ter a chance de se aquecer em dias de frio, dentre outras coisas.</p>
      </div>
      <div class="card" data-price="sugestões de doação" onclick="expandCard(event, this)">
        <img src="imagem3.png" alt="sugestões de doação">
        <h2>Sugestões de doação</h2><br>
        <p>Lista de sugestão de doação aqui.</p>
      </div>
    </div>
  </section>

  <footer>
    <p>Sistema de Doações FMP.</p>
    <p>Alunos: Issaga Seco Injai, Maria Eduarda Schmidt e Raissa Vieira.</p>
    <p>Implementado em xx/xx/xxxx.</p>
  </footer>

  <script>
    const frases = [
      { texto: "Não podemos ajudar a todos, mas todos podem ajudar alguém.", autor: "Ronald Reagan" },
      { texto: "A maior das riquezas é a generosidade.", autor: "Sêneca" },
      { texto: "A verdadeira felicidade está em ajudar ao próximo.", autor: "Albert Schweitzer" },
      { texto: "A verdadeira medida da vida é o que fazemos pelos outros.", autor: "Albert Einstein" },
      { texto: "Ninguém jamais se tornou pobre por doar.", autor: "Anne Frank" },
      { texto: "Seja a mudança que você quer ver no mundo.", autor: "Mahatma Gandhi" },
      { texto: "A generosidade é o investimento que rende os maiores dividendos.", autor: "Henry David Thoreau" },
      { texto: "As melhores e mais belas coisas do mundo não podem ser vistas ou tocadas, mas são sentidas no coração.", autor: "Helen Keller" },
      { texto: "A compaixão e a empatia são a essência da verdadeira humanidade.", autor: "Dalai Lama" },
    ];

    function exibirFraseAleatoria() {
      const index = Math.floor(Math.random() * frases.length);
      document.getElementById('frase').innerText = `"${frases[index].texto}"`;
      document.getElementById('autor').innerText = frases[index].autor;
    }

    window.onload = exibirFraseAleatoria;

    function expandCard(event, card) {
      event.stopPropagation();
      const expandedCard = document.querySelector('.card.expanded');
      if (expandedCard && expandedCard !== card) {
        minimizeCard(event, expandedCard);
      }
      card.classList.add('expanded');
      card.querySelector('p').style.maxHeight = 'none';
      if (!card.querySelector('.minimize-button')) {
        card.innerHTML += `<button class="minimize-button" onclick="minimizeCard(event, this.parentNode)">Minimizar</button>`;
      }
      document.querySelectorAll('.card:not(.expanded)').forEach(c => {
        c.style.display = 'none';
      });
    }

    function minimizeCard(event, card) {
      event.stopPropagation();
      card.classList.remove('expanded');
      card.querySelector('p').style.maxHeight = '3em';
      const minimizeButton = card.querySelector('.minimize-button');
      if (minimizeButton) {
        minimizeButton.remove();
      }
      document.querySelectorAll('.card').forEach(c => {
        c.style.display = 'block';
      });
    }
  </script>
</body>
</html>
