FROM mattrayner/lamp:latest
COPY app/src/ /app/
COPY app/jeuxcombat11.sql /tmp/