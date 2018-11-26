<div class="row reset-pass" data-id="<?=$this->var['id'];?>" data-token="<?=$this->var['token'];?>">
    <main class="col-lg-12 d-flex flex-row ">
        <form class="col-5 mx-auto py-3 border">
            <h3 class="text-center">Réinitialisation du mot de passe</h3>

            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" class="form-control password" placeholder="mot de passe">
            </div>
            <div class="form-group">
                <label>Confirmation du nouveau mot de passe</label>
                <input type="password" class="form-control password-confirm" placeholder="confirmation">
            </div>

            <button type="submit" class="btn btn-primary d-block mx-auto mt-auto">Envoyer</button>
        </form>
    </main>
</div>