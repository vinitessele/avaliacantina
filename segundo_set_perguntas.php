<?php
require_once "config.php"; // usa a conexão PDO já definida
// Processar formulário se foi enviado
if ($_POST) {
    $cantina = $_POST['cantina'];
    $variedade = $_POST['variedade'];
    $alimentos = $_POST['alimentos'];
    $tempo_atendimento = $_POST['tempo_atendimento'];
    $comentarios = $_POST['comentarios'] ?? '';

    $ip = getUserIP();

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS segundo_set_avaliacoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cantina VARCHAR(255) NOT NULL,
            variedade INT NOT NULL,
            alimentos INT NOT NULL,
            tempo_atendimento INT NOT NULL,
            comentarios VARCHAR(500),
            data_avaliacao DATETIME NOT NULL
        )");

        $sql = "INSERT INTO segundo_set_avaliacoes (cantina, variedade, alimentos, tempo_atendimento, comentarios, data_avaliacao) 
                VALUES (:cantina, :variedade, :alimentos, :tempo_atendimento, :comentarios, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cantina' => $cantina,
            ':variedade' => $variedade,
            ':alimentos' => $alimentos,
            ':tempo_atendimento' => $tempo_atendimento,
            ':comentarios' => $comentarios,
        ]);

        header("Location: ". $_SERVER['PHP_SELF']. "?sucesso=1");
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao salvar avaliação: " . $e->getMessage();
    }
}


function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP compartilhado
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Pode ter múltiplos IPs (proxy, load balancer)
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação das Cantinas - Faculdade Donaduzzi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="mobile-app">
    <!-- Header moderno -->
    <header class="app-header">
        <div class="header-content">
            <div class="logo-area">
                <i class="fas fa-utensils"></i>
                <div>
                    <h1>Avalie Nossa Cantina</h1>
                    <p>Faculdade Donaduzzi</p>
                </div>
            </div>
            <div class="header-subtitle">
                Sua opinião é muito importante! 💜
            </div>
        </div>
        <div class="header-wave"></div>
    </header>

    <!-- Container principal -->
    <main class="main-content">
        <!-- Mensagens de feedback -->
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success" id="success-alert">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Muito obrigado!</strong><br>
                    Sua avaliação foi enviada com sucesso! 🎉
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Oops!</strong><br>
                    <?php echo $erro; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulário de avaliação -->
        <form method="POST" action="" class="evaluation-form" id="evaluationForm">
            <!-- Seleção da cantina -->
            <div class="form-section">
                <div class="section-header">
                    <div class="step-number">1</div>
                    <h2>Escolha a Cantina</h2>
                </div>

                <div class="cantina-selector">
                    <label class="cantina-option">
                        <input type="radio" name="cantina" value="Cantina Charles Darwin" required>
                        <div class="cantina-card">
                            <div class="cantina-icon">🍰</div>
                            <div class="cantina-info">
                                <h3>Dolce Delicias</h3>
                                <p>Salgados e Doces</p>
                            </div>
                            <div class="check-mark"><i class="fas fa-check"></i></div>
                        </div>
                    </label>

                    <label class="cantina-option">
                        <input type="radio" name="cantina" value="Adriano Chagas Burguer">
                        <div class="cantina-card">
                            <div class="cantina-icon">🍔</div>
                            <div class="cantina-info">
                                <h3>Adriano Chagas</h3>
                                <p>Burgers e Salgados</p>
                            </div>
                            <div class="check-mark"><i class="fas fa-check"></i></div>
                        </div>
                    </label>

                    <label class="cantina-option">
                        <input type="radio" name="cantina" value="Delícias Gurmet">
                        <div class="cantina-card">
                            <div class="cantina-icon">⭐</div>
                            <div class="cantina-info">
                                <h3>Delícias Gourmet</h3>
                                <p>Churros Gourmet e Shawarma</p>
                            </div>
                            <div class="check-mark"><i class="fas fa-check"></i></div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Avaliações -->
            <div class="form-section">
                <div class="section-header">
                    <div class="step-number">2</div>
                    <h2>Avalie os Serviços</h2>
                </div>

                <!-- Higiene -->
                <div class="rating-card">
                    <h3>Como você avalia a variedade do cardápio e das bebidas disponíveis?</h3>
                    <div class="emoji-rating">
                        <?php
                        $cardapioOptions = [
                            1 => ['emoji' => '😤', 'label' => 'Muito Ruim'],
                            2 => ['emoji' => '😕', 'label' => 'Ruim'],
                            3 => ['emoji' => '😐', 'label' => 'Regular'],
                            4 => ['emoji' => '😊', 'label' => 'Bom'],
                            5 => ['emoji' => '😍', 'label' => 'Excelente']
                        ];
                        foreach ($cardapioOptions as $value => $option): ?>
                            <label class="emoji-option">
                                <input type="radio" name="variedade" value="<?php echo $value; ?>" required>
                                <div class="emoji-content">
                                    <span class="emoji"><?php echo $option['emoji']; ?></span>
                                    <span class="label"><?php echo $option['label']; ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Preços -->
                <div class="rating-card">
                    <h3>Quanto você considera que os alimentos da cantina são frescos e de qualidade?</h3>
                    <div class="emoji-rating">
                        <?php
                        $alimentosOptions = [
                            1 => ['emoji' => '💸', 'label' => 'Muito Caro'],
                            2 => ['emoji' => '💰', 'label' => 'Caro'],
                            3 => ['emoji' => '💵', 'label' => 'Justo'],
                            4 => ['emoji' => '💲', 'label' => 'Barato'],
                            5 => ['emoji' => '🤑', 'label' => 'Muito Barato']
                        ];
                        foreach ($alimentosOptions as $value => $option): ?>
                            <label class="emoji-option">
                                <input type="radio" name="alimentos" value="<?php echo $value; ?>" required>
                                <div class="emoji-content">
                                    <span class="emoji"><?php echo $option['emoji']; ?></span>
                                    <span class="label"><?php echo $option['label']; ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Atendimento -->
                <div class="rating-card">
                    <h3>Como você avalia a agilidade/tempo de espera no atendimento?</h3>
                    <div class="emoji-rating">
                        <?php
                        $atendimentoOptions = [
                            1 => ['emoji' => '😠', 'label' => 'Péssimo'],
                            2 => ['emoji' => '😒', 'label' => 'Ruim'],
                            3 => ['emoji' => '😌', 'label' => 'Regular'],
                            4 => ['emoji' => '😄', 'label' => 'Bom'],
                            5 => ['emoji' => '🥰', 'label' => 'Excelente']
                        ];
                        foreach ($atendimentoOptions as $value => $option): ?>
                            <label class="emoji-option">
                                <input type="radio" name="tempo_atendimento" value="<?php echo $value; ?>" required>
                                <div class="emoji-content">
                                    <span class="emoji"><?php echo $option['emoji']; ?></span>
                                    <span class="label"><?php echo $option['label']; ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Comentários -->
            <div class="form-section">
                <div class="section-header">
                    <div class="step-number">3</div>
                    <h2>Comentários</h2>
                </div>

                <div class="comment-card">
                    <label for="comentarios">Conte-nos sua experiência:</label>
                    <textarea
                        name="comentarios"
                        id="comentarios"
                        placeholder="O que mais gostou? O que pode melhorar? Deixe sua sugestão..."
                        rows="4"
                    ></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span>/500 caracteres
                    </div>
                </div>
            </div>

            <!-- Botão de envio -->
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    <span>Enviar Avaliação</span>
                    <div class="btn-shine"></div>
                </button>
            </div>
        </form>
    </main>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Função para mostrar alertas personalizados
    function showAlert(message, type = 'info') {
      const alert = document.createElement('div');
      alert.className = `alert alert-${type} alert-floating`;
      alert.innerHTML = `
                    <i class="fas fa-info-circle"></i>
                    <div>${message}</div>
                `;

      document.body.appendChild(alert);

      setTimeout(() => alert.classList.add('show'), 100);
      setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 300);
      }, 4000);
    }


    // Melhorar interação com seleção de cantinas

    document.querySelectorAll('.cantina-option').forEach(option => {
      option.addEventListener('click', function() {
        document.querySelectorAll('.cantina-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        this.classList.add('selected');

        // Vibração haptica se disponível
        if (navigator.vibrate) {
          navigator.vibrate(50);
        }
      });
    });

    // Melhorar interação com emojis
    document.querySelectorAll('.emoji-option').forEach(option => {
      option.addEventListener('click', function() {
        const container = this.closest('.emoji-rating');
        container.querySelectorAll('.emoji-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        this.classList.add('selected');

        // Vibração haptica
        if (navigator.vibrate) {
          navigator.vibrate(30);
        }
      });
    });

    // Contador de caracteres
    const textarea = document.getElementById('comentarios');
    const counter = document.getElementById('charCount');

    textarea.addEventListener('input', function() {
      const length = this.value.length;
      counter.textContent = length;

      if (length > 450) {
        counter.style.color = '#e74c3c';
      } else if (length > 400) {
        counter.style.color = '#f39c12';
      } else {
        counter.style.color = '#666';
      }

      // Auto-resize textarea
      this.style.height = 'auto';
      this.style.height = this.scrollHeight + 'px';
    });

    // Validação visual do formulário
    const form = document.getElementById('evaluationForm');
    form.setAttribute('novalidate', 'true'); // Desabilita validação HTML5


    form.addEventListener('submit', function(e) {
      e.preventDefault()
      // Verificar se todos os campos obrigatórios estão preenchidos
      const requiredRadios = ['cantina', 'variedade', 'alimentos', 'tempo_atendimento'];
      let firstInvalidField = null;

      for (let name of requiredRadios) {
        const checked = document.querySelector(`input[name="${name}"]:checked`);
        if (!checked) {
          // Encontrar o primeiro radio do grupo
          const firstRadio = document.querySelector(`input[name="${name}"]`);
          if (firstRadio) {
            firstInvalidField = firstRadio;
            break;
          }
        }
      }

      if (firstInvalidField) {
        e.preventDefault(); // Impedir envio

        // Scroll suave até o campo
        firstInvalidField.closest('.form-section, .rating-card').scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });

        // Aguardar o scroll terminar antes de tentar focar
        setTimeout(() => {
          // Remover required temporariamente para evitar o erro
          const allRequired = document.querySelectorAll('input[required]');
          allRequired.forEach(input => input.removeAttribute('required'));

          // Mostrar alerta personalizado
          showAlert('Por favor, complete todos os campos obrigatórios!', 'warning');

          // Adicionar animação de shake
          const section = firstInvalidField.closest('.form-section, .rating-card');
          section.classList.add('shake');
          setTimeout(() => section.classList.remove('shake'), 600);

          // Restaurar required após um tempo
          setTimeout(() => {
            allRequired.forEach(input => input.setAttribute('required', ''));
          }, 1000);
        }, 500);

        return false;
      }

      // Loading state no botão
      const submitButton = document.querySelector('.submit-btn');
      submitButton.classList.add('loading');
      submitButton.innerHTML = `
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Enviando...</span>`;


      form.submit()
    });

    // Animações de entrada
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.form-section, .rating-card').forEach(el => {
      observer.observe(el);
    });

  });

  //alert-success desaparecer após 5 seg
  const successAlert = document.getElementById('success-alert')

  if (successAlert){
    const url = new URL(window.location);
    if (url.searchParams.has('sucesso')) {
      url.searchParams.delete('sucesso');
      window.history.replaceState({}, document.title, url.toString());
    }

    setTimeout(() => {
      successAlert.style.transition = 'opacity 1s ease';
      successAlert.style.opacity = '0';
      setTimeout(() => {successAlert.remove()}, 1000)
    }, 5000)
  }
</script>
</body>
</html>