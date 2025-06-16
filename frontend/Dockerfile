# Use official Node.js image
FROM node:20

# Create app directory
WORKDIR /app

# Install app dependencies
COPY package*.json ./
RUN npm install

# Copy source files
COPY . .

# Ensure file change detection in Docker (esp. on Windows)
ENV HOST=0.0.0.0
ENV CHOKIDAR_USEPOLLING=true
ENV WATCHPACK_POLLING=true

# Expose port
EXPOSE 3000

# Start dev server
CMD ["npm", "start"]
