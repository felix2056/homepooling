<?php

// namespace \App\Services;
// 	
// use \App\FBlogin;
// use \App\User;
// use Laravel\Socialite\Contracts\User as ProviderUser;
// 	
// class FBloginService{
// 	public function createOrGetUser(ProviderUser $providerUser){
// 		
// 		$account=\App\FBlogin::whereProvider('facebook')->whereProviderUserId($providerUser->getId())->first();
// 		if($account){
// 			return $account->user;
// 		}else{
// 			$account=new \App\FBlogin([
// 				'provider_user_id'=>$providerUser->getId(),
// 				'provider'=>'facebook',
// 			]);
// 			$user=\App\User::whereEmail($providerUser->getEmail()->first());
// 			if(!$user){
// 				$user=\App\User:create([
// 					'email'=>$providerUser->getEmail(),
// 					'name'=>$providerUser->getName(),
// 					'family_name'=>'',
// 					'photo'=>$providerUser->getAvatar(),
// 					'password'=>md5(rand(1,10000)),
// 				]);
// 			}
// 			$account->user()->associate($user);
// 			$account->save();
// 			return $user;
// 		}
// 	}
// }
 
?>
