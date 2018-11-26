<div class="row">
    <main class="col-lg-12 ">
        <form class="col-5 mx-auto py-3 border">
            <h3 class="text-center">Se connecter</h3>
            <div class="form-group">
                <label>Adresse mail</label>
                <input type="email" class="form-control email" placeholder="example@mail.com">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" class="form-control password" placeholder="mot de passe">
            </div>
            <div class="form-group d-flex"> 
                    Pas encore inscrit ? 
                    <a class="text-blue ml-1" href="<?= $this->router->generate('register');?>">S'inscrire.</a>
                    <a class="text-blue ml-2" href="<?= $this->router->generate('resetPassword');?>">Mot de passe oublié ? </a>
            </div>
            <button type="submit" class="btn btn-primary d-block mx-auto mt-auto">Connexion</button>
        </form>
    </main>
</div>