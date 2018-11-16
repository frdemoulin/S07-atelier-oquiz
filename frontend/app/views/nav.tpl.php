<nav class="navbar navbar-toggleable-md navbar-light bg-faded">

    <ul class="nav mr-auto">
        <li class="nav-item">
            <a class="nav-link d-inline text-blue" href="<?= $this->router->generate('home');?>">
            <h1 >O'Quiz</h1>
            </a>
        </li>
    </ul>

    <ul class="nav nav-pills justify-content-end">
        <li class="nav-item">
            <a class="nav-link active" href="<?= $this->router->generate('home');?>">
                <i class="fas fa-home"></i>
                Accueil
            </a>
        </li>

<?php if (!empty($_SESSION['userId'])) : ?>
        <li class="nav-item">
            <a class="nav-link text-blue" href="<?= $this->router->generate('account');?>">
                <i class="fas fa-user"></i>
                Mon compte
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-blue" href="<?= $this->router->generate('signIn');?>?disconnect=1">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </li>
<?php else : ?>
<li class="nav-item">
            <a class="nav-link text-blue" href="<?= $this->router->generate('signIn');?>">
                <i class="fas fa-user"></i>
                Se connecter
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-blue" href="<?= $this->router->generate('register');?>">
                <i class="fas fa-sign-out-alt"></i>
                S'inscrire
            </a>
        </li>
<?php endif; ?>
    </ul>
</nav>