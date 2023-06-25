
> adduser blink
> usermod -aG sudo blink


# disable root login
> sudo vim  /etc/ssh/sshd_config
> systemctl reload ssh

# copy-ssh key
ssh-copy-id mobiadi@159.89.8.177
