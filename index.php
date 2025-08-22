<?php
// config.php - Configuração do banco de dados
$host = 'localhost';
$dbname = 'faculdade_donaduzzi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Processar formulário se foi enviado
if ($_POST) {
    $cantina = $_POST['cantina'];
    $higiene = $_POST['higiene'];
    $precos = $_POST['precos'];
    $atendimento = $_POST['atendimento'];
    $comentarios = $_POST['comentarios'] ?? '';
    
    try {
        $sql = "INSERT INTO avaliacoes (cantina, higiene, precos, atendimento, comentarios, data_avaliacao) 
                VALUES (:cantina, :higiene, :precos, :atendimento, :comentarios, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cantina' => $cantina,
            ':higiene' => $higiene,
            ':precos' => $precos,
            ':atendimento' => $atendimento,
            ':comentarios' => $comentarios
        ]);
        
        $sucesso = true;
    } catch(PDOException $e) {
        $erro = "Erro ao salvar avaliação: " . $e->getMessage();
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
            <?php if (isset($sucesso)): ?>
                <div class="alert alert-success">
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
                        <h3>Como você avalia a higiene e limpeza?</h3>
                        <div class="emoji-rating">
                            <?php
                            $higieneOptions = [
                                1 => ['emoji' => '😤', 'label' => 'Muito Ruim'],
                                2 => ['emoji' => '😕', 'label' => 'Ruim'],
                                3 => ['emoji' => '😐', 'label' => 'Regular'],
                                4 => ['emoji' => '😊', 'label' => 'Bom'],
                                5 => ['emoji' => '😍', 'label' => 'Excelente']
                            ];
                            foreach ($higieneOptions as $value => $option): ?>
                                <label class="emoji-option">
                                    <input type="radio" name="higiene" value="<?php echo $value; ?>" required>
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
                        <h3>Como você avalia os preços dos produtos?</h3>
                        <div class="emoji-rating">
                            <?php
                            $precosOptions = [
                                1 => ['emoji' => '💸', 'label' => 'Muito Caro'],
                                2 => ['emoji' => '💰', 'label' => 'Caro'],
                                3 => ['emoji' => '💵', 'label' => 'Justo'],
                                4 => ['emoji' => '💲', 'label' => 'Barato'],
                                5 => ['emoji' => '🤑', 'label' => 'Muito Barato']
                            ];
                            foreach ($precosOptions as $value => $option): ?>
                                <label class="emoji-option">
                                    <input type="radio" name="precos" value="<?php echo $value; ?>" required>
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
                        <h3>Como avalia o atendimento dos funcionários?</h3>
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
                                    <input type="radio" name="atendimento" value="<?php echo $value; ?>" required>
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
            form.addEventListener('submit', function(e) {
                const submitBtn = document.querySelector('.submit-btn');
                
                // Verificar se todos os campos obrigatórios estão preenchidos
                const requiredRadios = ['cantina', 'higiene', 'precos', 'atendimento'];
                let isValid = true;
                
                requiredRadios.forEach(name => {
                    if (!document.querySelector(`input[name="${name}"]:checked`)) {
                        isValid = false;
                        const section = document.querySelector(`input[name="${name}"]`).closest('.form-section, .rating-card');
                        section.classList.add('shake');
                        setTimeout(() => section.classList.remove('shake'), 600);
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Mostrar alerta personalizado
                    showAlert('Por favor, complete todos os campos obrigatórios!', 'warning');
                    
                    // Scroll para o primeiro campo não preenchido
                    const firstEmpty = document.querySelector('.shake');
                    if (firstEmpty) {
                        firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    return;
                }
                
                // Loading state no botão
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = `
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Enviando...</span>
                `;
            });

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
    </script>
</body>
</html>