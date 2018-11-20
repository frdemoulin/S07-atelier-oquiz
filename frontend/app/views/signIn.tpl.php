<div class="row">
    <main class="col-lg-12 d-flex flex-row ">
        <form class="col-5 mx-auto py-3 border">
            <h3 class="text-center">Se connecter</h3>
            <div class="form-group">
                <label>Adresse mail</label>
                <input type="email" class="form-control email" placeholder="example@mail.com">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" class="form-control password" placeholder="Password">
            </div>
            <div class="form-group justify-content-center"> 
                <span>Pas encore inscrit ? </span>
                <a class="nav-link text-blue" href="<?= $this->router->generate('register');?>">S'inscrire.</a>
            </div>
            <button type="submit" class="btn btn-primary d-block mx-auto mt-auto">Connexion</button>
        </form>
    </main>
</div>